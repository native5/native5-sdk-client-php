<?php
/**
 *  Copyright 2013 Native5. All Rights Reserved
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *	You may not use this file except in compliance with the License.
 *
 *	Unless required by applicable law or agreed to in writing, software
 *	distributed under the License is distributed on an "AS IS" BASIS,
 *	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *	See the License for the specific language governing permissions and
 *	limitations under the License.
 *  PHP version 5.3+
 *
 * @category  Native5 App Account Services
 * @package   Native5\Accounts
 * @author    Shamik Datta <shamik@native5.com>
 * @copyright 2013 Native5. All Rights Reserved 
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$ 
 * @link      http://www.docs.native5.com 
 */

namespace Native5\Services\Account;

use Native5\Api\ApiClient;

use Upload\Storage\FileSystem;
use Upload\File;
use Upload\Validation\Mimetype;
use Upload\Validation\Size;

/**
 * DefaultAccountManager 
 * 
 * @uses    ApiClient
 * @uses    AccountManager
 * @version $id$
 * @author  Shamik Datta <shamik@native5.com>
 */
class DefaultAccountManager extends ApiClient implements AccountManager {
    // If you add a constructor here you need to explicitly call the super constructor

    public function getAccountDetails($code) {
        $path = 'accounts/'.$code;
        $request =  $this->_remoteServer->get($path);
        $response = $request->send();

        return $response->getBody(true);
    }

    public function getApps($code) {
        $path = 'accounts/'.$code.'/apps';
        $request =  $this->_remoteServer->get($path);
        $response = $request->send();

        $appsData = $response->json();
        $GLOBALS['logger']->info("Got JSON response: ".print_r($appsData, 1), array());

        $apps = array();
        $appDAO = new ApplicationDAO();
        // Build Application objects from the array data
        foreach ($appsData as $idx=>$app)
            $apps[] = $appDAO->buildApp($app);

        return $apps;
    }

    public function createApp($code, $appName, $options) {
        $path = 'accounts/'.$code.'/app';
        $request = $this->_remoteServer->post($path)
            ->setPostField('name', $appName)
            ->setPostField('options', json_encode($options))
            ->addPostFile('file', '@/dev/null'); // To force header multipart/form-data
        $GLOBALS['logger']->info("Path: $path | name: $appName | options: ".PHP_EOL.print_r($options, 1));
        try {
            $response = $request->send();
        } catch(\Guzzle\Http\Exception\BadResponseException $e) {
            $GLOBALS['logger']->info($e->getResponse()->getBody('true'), array());
            return false;
        }

        // Long running job
        $respBody = $response->getBody(true);
        $GLOBALS['logger']->info("Got long running job id: ".$respBody, array());

        return $respBody;
    }

    public function uploadApp($code, $appName, $appPackageKeyname) {
        if (!($appPackagePath = $this->_uploadAppPackage($appName, $appPackageKeyname)))
            return false;
        
        if (!($serverPackagePath = $this->_scpAppPackage($code, $appPackagePath)))
            return false;
        
        return array(
            'server' => self::APP_FTP_SERVER_DN,
            'path' => $serverPackagePath,
            'user' => self::APP_FTP_SERVER_USER
        );
    }

    const APP_FTP_SERVER_DN = 'uploads.native5.com';
    const APP_FTP_SERVER_PORT = 22;
    const APP_FTP_SERVER_USER = 'n5ftpadmin';
    const APP_FTP_SERVER_USER_KEYNAME = 'n5sandboxftp';
    const APP_FTP_SERVER_DIRECTORY = '/home/n5ftpadmin/sandboxApps';

    private function _scpAppPackage($code, $pkgPath) {
        $keyDir = __DIR__."/keys";
        $publicKey = $keyDir.'/'.self::APP_FTP_SERVER_USER_KEYNAME.'.pub';
        $privateKey = $keyDir.'/'.self::APP_FTP_SERVER_USER_KEYNAME;

        $session = ssh2_connect(self::APP_FTP_SERVER_DN, self::APP_FTP_SERVER_PORT);

        ssh2_auth_pubkey_file(
            $session,
            self::APP_FTP_SERVER_USER,
            $publicKey,
            $privateKey
        );

        // Create a directory for this account
        $serverPackageDir = self::APP_FTP_SERVER_DIRECTORY."/$code";
        $serverPackagePath = "$serverPackageDir/".basename($pkgPath);
        $remoteDirCmd = "cd $serverPackageDir | mkdir $serverPackageDir";
        ssh2_exec($session, $remoteDirCmd);

        $ssh2Wrapper = 'ssh2.sftp://'.$session.$serverPackagePath;
        if (@file_put_contents($ssh2Wrapper, fopen($pkgPath, "r")))
            return $serverPackagePath;
        else
            return false;
    }

    private function _uploadAppPackage($appName, $appPackageKeyname) {
        $uploadedDir = __DIR__."/tmp";
        if (!is_dir($uploadedDir))
            mkdir($uploadedDir, 0755);

        $uploadedFileName = $appName."_".mt_rand()."_".time();
        
        $storage = new FileSystem($uploadedDir);
        $file = new File($appPackageKeyname, $storage);
        $file->setName($uploadedFileName);
        
        $file->addValidations(array(
            // FIXME: Specify only compressed file extensions
            new Mimetype(array('application/x-gzip', 'application/gnutar', 'application/x-compressed')),

            // Ensure file is no larger than 5M (use "B", "K", M", or "G")
            new Size('5M')
        ));

        try {
            $file->upload();
        } catch (\Exception $e) {
            $GLOBALS['logger']->error("File upload Error: ".print_r($file->getErrors(), 1));
            return false;
        }

        return $uploadedDir.'/'.$uploadedFileName.'.'.$file->getExtension();
    }
}


