<?php


namespace app\modules\news\helpers;


class PaginationHelper
{
    const DEFAULT__PAGE_SIZE = 10;
    const USER__SESSION_PAGE_SIZE_KEY = "news__page-size";

    public static $allowedPageSizes = [
        5,
        10,
        15,
        30,
        60
    ];

    /**
     * @return array
     */
    public static function getAllowedPageSizesSelection()
    {
        $result = [];
        foreach (self::$allowedPageSizes as $size) {
            $result[$size] = $size;
        }

        return $result;
    }

    /**
     * @return int
     */
    public static function getPageSize()
    {
        return \Yii::$app->session->get(
            self::USER__SESSION_PAGE_SIZE_KEY,
            self::DEFAULT__PAGE_SIZE
        );
    }

    /**
     * @return int
     */
    public static function getCurrentPageSize()
    {
        return \Yii::$app->session->get(
            self::USER__SESSION_PAGE_SIZE_KEY,
            self::DEFAULT__PAGE_SIZE
        );
    }

    /**
     * @param int $pageSize
     * @return void
     */
    public static function setPageSize($pageSize)
    {
        if (!in_array($pageSize, self::$allowedPageSizes)) {
            $pageSize = self::DEFAULT__PAGE_SIZE;
        }

        \Yii::$app->session->set(
            self::USER__SESSION_PAGE_SIZE_KEY,
            $pageSize
        );
    }
}