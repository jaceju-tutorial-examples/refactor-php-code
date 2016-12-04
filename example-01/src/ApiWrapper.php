<?php

namespace Refactoring\Example01;

class ApiWrapper
{
    private $api;

    public function __construct()
    {
        $this->api = new Api();
    }

    /**
     * @param $indexes
     * @param array $params
     *
     * @return array|bool
     */
    private function getImageInfo($indexes, array $params)
    {
        if (false === is_array($indexes) || (0 === count($indexes))) {
            return false;
        }
        $params['indexes'] = $indexes;

        try {
            $this->api->checkParams($params);

            return $this->api->execute();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $indexes
     * @param array $params
     *
     * @return array|bool
     */
    private function getImagePaths($indexes, array $params)
    {
        $info = $this->getImageInfo($indexes, $params);
        $paths = [];
        if (is_array($info)) {
            foreach ($info as $key => $item) {
                $paths[$key] = $item['path'];
            }
        }
        return $paths;
    }

    /**
     * @param $indexes
     * @param array $params
     *
     * @return array|bool
     */
    public function getAlbumImageInfo($indexes, array $params = [])
    {
        $defaultParams = [
            'method' => 'list',
            'type' => 'album',
            'dimension' => '100x100',
        ];

        $newParams = array_merge(
            $defaultParams,
            $params
        );

        return $this->getImageInfo($indexes, $newParams);
    }

    /**
     * @param $indexes
     * @param array $params
     *
     * @return array|bool
     */
    public function getAlbumImagePaths($indexes, array $params = [])
    {
        $defaultParams = [
            'method' => 'list',
            'type' => 'album',
            'dimension' => '100x100',
        ];

        $newParams = array_merge(
            $defaultParams,
            $params
        );

        return $this->getImagePaths($indexes, $newParams);
    }

    /**
     * @param $indexes
     * @param array $params
     *
     * @return array|bool
     */
    public function getArticleImageInfo($indexes, array $params = [])
    {
        $defaultParams = [
            'method' => 'list',
            'type' => 'article',
            'dimension' => '100x100',
        ];

        $newParams = array_merge(
            $defaultParams,
            $params
        );

        return $this->getImageInfo($indexes, $newParams);
    }

    /**
     * @param $indexes
     * @param array $params
     *
     * @return array|bool
     */
    public function getArticleImagePaths($indexes, array $params = [])
    {
        $defaultParams = [
            'method' => 'list',
            'type' => 'article',
            'dimension' => '100x100',
        ];

        $newParams = array_merge(
            $defaultParams,
            $params
        );

        return $this->getImagePaths($indexes, $newParams);
    }
}
