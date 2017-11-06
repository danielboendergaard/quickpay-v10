<?php

namespace Kameli\Quickpay\Services;

use CURLFile;
use Kameli\Quickpay\Entities\Branding;

class Brandings extends Service
{
    /**
     * Get all brandings
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Branding[]
     */
    public function all($parameters = null)
    {
        return $this->createCollection($this->client->request('GET', '/brandings', $parameters), Branding::class);
    }

    /**
     * Create a branding
     * @param string $name
     * @return \Kameli\Quickpay\Entities\Branding
     */
    public function create($name)
    {
        return new Branding($this->client->request('POST', '/brandings', ['name' => $name]));
    }

    /**
     * Get branding
     * @param int $id
     * @return \Kameli\Quickpay\Entities\Branding
     */
    public function get($id)
    {
        return new Branding($this->client->request('GET', "/brandings/{$id}"));
    }

    /**
     * Update a branding
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Branding
     */
    public function update($id, $parameters)
    {
        return new Branding($this->client->request('PATCH', "/brandings/{$id}", $parameters));
    }

    /**
     * Delete a branding
     * @param int $id
     */
    public function delete($id)
    {
        $this->client->request('DELETE', "/brandings/{$id}");
    }

    /**
     * Copy a branding
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Branding
     */
    public function copy($id, $parameters)
    {
        return new Branding($this->client->request('POST', "/brandings/{$id}/copy", $parameters));
    }

    /**
     * Get branding resource file
     * @param int $id
     * @param string $resourceName
     * @return string
     */
    public function getResource($id, $resourceName)
    {
        return $this->client->requestRaw('GET', "/brandings/{$id}/{$resourceName}");
    }

    /**
     * Update branding resource file
     * @param int $id
     * @param string $resourceName
     * @param string $filePath
     * @return string
     */
    public function updateResource($id, $resourceName, $filePath)
    {
        return $this->client->request('PUT', "/brandings/{$id}/{$resourceName}", [
            'file' => new CURLFile($filePath),
        ]);
    }

    /**
     * Delete branding resource file
     * @param int $id
     * @param string $resourceName
     * @return string
     */
    public function deleteResource($id, $resourceName)
    {
        return $this->client->request('DELETE', "/brandings/{$id}/{$resourceName}");
    }
}
