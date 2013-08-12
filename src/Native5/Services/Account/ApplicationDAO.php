<?php
/**
 *  Copyright 2012 Native5. All Rights Reserved
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
 * @category        Native5 Application Services
 * @package         Native5\Core\<package>
 * @author          Shamik <shamik@native5.com>
 * @copyright       2013 Native5. All Rights Reserved 
 * @license         See attached LICENSE for details
 * @version         GIT: $gitid$ 
 * @link            http://www.docs.native5.com 
 */

namespace Native5\Services\Account;

class ApplicationDAO {
    public function buildApp($data) {
        $app = new Application($data['name']);
        if (isset($data['urls']) && !empty($data['urls'])) {
            if (is_array($data['urls'])) {
                foreach ($data['urls'] as $env=>$url)
                    $app->setUrl(array($env => $url));
            } else {
                $app->setUrl(array('sandbox' => $data['urls']));
            }
        }
        $created = (!empty($data['created']) ? $data['created'] : (!empty($data['created_on']) ? $data['created_on'] : null));
        $modified = (!empty($data['modified']) ? $data['modified'] : (!empty($data['modified_on']) ? $data['modified_on'] : null));

        $app->setCreatedOn($created);
        $app->setModifiedOn($modified);
        $app->setPlatforms($data['platforms']);

        // Repository config
        $appRepo = new ApplicationRepository();
        $appRepo->setType($data['repository']['type']);
        $appRepo->setUrl($data['repository']['url']);
        $app->setRepositoryConfig($appRepo);

        // Deployment configurations - more than one for load balanced nodes
        foreach ($data['deployment_metadata'] as $deploy) {
            $appDeploy = new ApplicationDeployment();
            $appDeploy->setServerName($deploy['name']);
            $appDeploy->setUserName($deploy['user']);
            $appDeploy->setDirectory($deploy['folder']);
            $appDeploy->setEnvironment($deploy['environment']);
            $app->addDeploymentConfig($appDeploy);
        }

        return $app;
    }

    private function _getStoreHandle() {
        $client = new \MongoClient();
        $db = $client->selectDB('native5');
        return $db->selectCollection('accounts');
    }
}


