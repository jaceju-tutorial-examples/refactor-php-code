<?php

namespace Refactoring\Example01;

use Doctrine\DBAL\DBALException;
use InvalidArgumentException;
use Refactoring\Example01\HttpRequest\CurlRequest;

class Api
{
    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @var array
     */
    private $params;

    /**
     * Api constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param HttpRequest $httpRequest
     */
    public function setHttpRequest($httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest()
    {
        if ($this->httpRequest) {
            return $this->httpRequest;
        }
        return new CurlRequest();
    }

    /**
     * @param array $params
     * @throws \InvalidArgumentException
     */
    public function checkParams(array $params)
    {
        // check method
        $allowedMethods = ['list', 'upload', 'update'];
        if (!isset($params['method'])) {
            throw new InvalidArgumentException('Missing `method`.');
        } elseif (!in_array($params['method'], $allowedMethods, true)) {
            throw new InvalidArgumentException("Method [{$params['method']}] is invalid.");
        }

        // is update
        if ('update' === $params['method']) {
            // check id
            if (!isset($params['id'])) {
                throw new InvalidArgumentException('Missing `id`.');
            }

            if (preg_match('/[\D]/', $params['id'])) {
                throw new InvalidArgumentException('Invalid id.');
            }

            // check action
            if (!isset($params['act'])) {
                throw new InvalidArgumentException('Missing `act`.');
            }

            $allowedAct = ['enable', 'disable'];
            if (!in_array($params['act'], $allowedAct, true)) {
                throw new InvalidArgumentException("Act [{$params['act']}] is invalid.");
            }
        } else {
            // check type
            $allowedTypes = ['album', 'article'];
            if (!isset($params['type'])) {
                throw new InvalidArgumentException('Missing `type`.');
            } elseif (!in_array($params['type'], $allowedTypes, true)) {
                throw new InvalidArgumentException("Type [{$params['type']}] is invalid.");
            }

            // check indexes
            if (!isset($params['indexes'])) {
                throw new InvalidArgumentException('Missing `indexes`.');
            } elseif (!is_array($params['indexes'])) {
                throw new InvalidArgumentException('Invalid indexes.');
            } else {
                $errorIndexes = [];
                foreach ($params['indexes'] as $index) {
                    if (preg_match('/^\D$/', $index)) {
                        $errorIndexes[] = $index;
                    }
                }
                if (!empty($errorIndexes)) {
                    $error = sprintf('Indexes [%s] contains illegal characters.', implode(', ', $errorIndexes));
                    throw new InvalidArgumentException($error);
                }
            }
        }

        $this->params = $params;
    }

    /**
     *
     * @return array
     */
    public function execute()
    {
        switch ($this->params['method']) {
            case 'upload':
                return $this->uploadImage();
            case 'update':
                return $this->updateImage();
            default:
                return $this->getInfo();
        }
    }

    /**
     * @return string
     */
    private function buildUrl()
    {
        $baseUrl = sprintf('http://localhost/%s/$s', $this->params['method'], $this->params['type']);
        if ($this->params['method'] === 'update') {
            return sprintf('%s/%s/%s.jpg', $baseUrl, $this->params['id'], $this->params['dimension']);
        }
        return $baseUrl;
    }

    /**
     * @return mixed
     */
    private function uploadImage()
    {
        $url = $this->buildUrl();
        $httpRequest = $this->getHttpRequest();
        $post = [
            'file' => $httpRequest->fileCreate($this->params['file']),
        ];
        $httpRequest->setOption(CURLOPT_URL, $url);
        $httpRequest->setOption(CURLOPT_HEADER, false);
        $httpRequest->setOption(CURLOPT_VERBOSE, false);
        $httpRequest->setOption(CURLOPT_POST, true);
        $httpRequest->setOption(CURLOPT_POSTFIELDS, $post);
        $httpRequest->setOption(CURLOPT_RETURNTRANSFER, true);
        $httpRequest->setOption(CURLOPT_AUTOREFERER, true);
        $httpRequest->setOption(CURLOPT_FOLLOWLOCATION, true);
        $result = $httpRequest->execute();
        $httpRequest->close();
        return $result;
    }

    /**
     * @return mixed
     */
    private function updateImage()
    {
        $url = $this->buildUrl();
        $httpRequest = $this->getHttpRequest();
        $httpRequest->setOption(CURLOPT_URL, $url);
        $httpRequest->setOption(CURLOPT_RETURNTRANSFER, true);
        $httpRequest->setOption(CURLOPT_AUTOREFERER, true);
        $httpRequest->setOption(CURLOPT_FOLLOWLOCATION, true);
        $result = $httpRequest->execute();
        $httpRequest->close();
        return $result;
    }

    /**
     * @return array
     */
    private function getInfo()
    {
        try {
            $db = Db::getInstance();
            $stmt = $db->createQuery()
                ->select('id', 'type')
                ->from('images')
                ->where('id IN (?)')
                ->andWhere('type = ?')
                ->setParameter(0, implode(', ', $this->params['indexes']))
                ->setParameter(1, $this->params['type'])
                ->execute();
            $result = $db->fetchAll($stmt);

            $info = [];
            foreach ($result as $row) {
                $info[$row['id']] = [
                    'id' => $row['id'],
                    'path' => $row['type'] . '/' . $row['id'] . '/' . $this->params['dimension'] . '.jpg',
                ];
            }
            return $info;
        } catch (DBALException $e) {
            return [];
        }
    }
}
