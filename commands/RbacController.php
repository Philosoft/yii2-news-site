<?php


namespace app\commands;


use app\modules\news\models\News;
use app\rbac\AuthorRule;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class RbacController
 * @package app\commands
 */
class RbacController extends Controller
{
    const ROLE__MANAGER = "manager";
    const ROLE__REGISTERED_USER = "ordinary-user";
    const ROLE__ADMIN = "admin";

    const USER_ID__ADMIN = 1;
    const USER_ID__MANAGER = 2;

    protected function addAuthItem($item)
    {
        try {
            \Yii::$app->authManager->add($item);
        } catch (\Exception $e) {
            $this->stderr("ERROR: ", Console::FG_RED);
            $this->stderr($e->getMessage() . PHP_EOL);
        }
    }

    protected function addAuthChild($parent, $child) {
        try {
            \Yii::$app->authManager->addChild($parent, $child);
        } catch (\Exception $e) {
            $this->stderr("ERROR: ", Console::FG_RED);
            $this->stderr($e->getMessage() . PHP_EOL);
        }
    }

    public function actionInit()
    {
        $auth = \Yii::$app->authManager;

        // read permission
        $readPostPermission = $auth->createPermission(News::PERMISSION__READ_POST);
        $this->addAuthItem($readPostPermission);

        // ordinary user can only view full articles
        $registeredUser = $auth->createRole(self::ROLE__REGISTERED_USER);
        $this->addAuthItem($registeredUser);
        $this->addAuthChild($registeredUser, $readPostPermission);

        // update permission
        $updatePermission = $auth->createPermission(News::PERMISSION__UPDATE);
        $this->addAuthItem($updatePermission);

        // update-own-post rule
        $isAuthorRule = new AuthorRule();
        $this->addAuthItem($isAuthorRule);
        $updateOwnPost = $auth->createPermission(News::PERMISSION__UPDATE_OWN_POST);
        $updateOwnPost->ruleName = $isAuthorRule->name;
        $this->addAuthItem($updateOwnPost);

        // as a part of update-post rule
        $this->addAuthChild($updateOwnPost, $updatePermission);

        // manage permission
        $managePermission = $auth->createPermission(News::PERMISSION__MANAGE);
        $this->addAuthItem($managePermission);

        $createPermission = $auth->createPermission(News::PERMISSION__CREATE_POST);
        $this->addAuthItem($createPermission);

        // role manager
        $manager = $auth->createRole(self::ROLE__MANAGER);
        $this->addAuthItem($manager);
        $this->addAuthChild($manager, $registeredUser);
        $this->addAuthChild($manager, $updateOwnPost);
        $this->addAuthChild($manager, $managePermission);
        $this->addAuthChild($manager, $createPermission);

        // delete permission
        $deletePermission = $auth->createPermission(News::PERMISSION__DELETE_POST);
        $this->addAuthItem($deletePermission);

        // role admin
        $admin = $auth->createRole(self::ROLE__ADMIN);
        $this->addAuthItem($admin);
        $this->addAuthChild($admin, $manager);
        $this->addAuthChild($admin, $updatePermission);
        $this->addAuthChild($admin, $deletePermission);

        try {
            $auth->assign($admin, self::USER_ID__ADMIN);
        } catch (\Exception $e) {}

        try {
            $auth->assign($manager, self::USER_ID__MANAGER);
        } catch (\Exception $e) {}
    }
}