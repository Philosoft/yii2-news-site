<?php


namespace app\commands;


use app\models\News;
use app\rbac\AuthorRule;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class RbacController
 * @package app\commands
 */
class RbacController extends Controller
{
    const ROLE_MANAGER = "manager";
    const ROLE_REGISTERED_USER = "ordinary-user";
    const ROLE_ADMIN = "admin";

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

        $readFullPost = $auth->createPermission(News::PERMISSION__VIEW_FULL_POST);
        $this->addAuthItem($readFullPost);

        $updatePost = $auth->createPermission(News::PERMISSION__EDIT);
        $this->addAuthItem($updatePost);

        $registeredUser = $auth->createRole(self::ROLE_REGISTERED_USER);
        $this->addAuthItem($registeredUser);
        $this->addAuthChild($registeredUser, $readFullPost);

        $manager = $auth->createRole(self::ROLE_MANAGER);
        $this->addAuthItem($manager);
        $this->addAuthChild($manager, $registeredUser);

        $admin = $auth->createRole(self::ROLE_ADMIN);
        $this->addAuthItem($admin);
        $this->addAuthChild($admin, $manager);
        $this->addAuthChild($admin, $updatePost);

        $isAuthorRule = new AuthorRule();
        $this->addAuthItem($isAuthorRule);
        $updateOwnPost = $auth->createPermission(News::PERMISSION__UPDATE_OWN_POST);
        $updateOwnPost->ruleName = $isAuthorRule->name;
        $this->addAuthItem($updateOwnPost);

        try {
            $auth->assign($admin, self::USER_ID__ADMIN);
        } catch (\Exception $e) {}

        try {
            $auth->assign($manager, self::USER_ID__MANAGER);
        } catch (\Exception $e) {}
    }
}