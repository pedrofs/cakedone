<?php
namespace App\Controller;

use Cake\Network\Exception\UnauthorizedException;

trait TrackableTrait
{
    /**
     * Start method will try to start a tacking over the target resource
     *
     * @param int $id The resource id
     * @return void Renders the tracking or the error when trying to start tracking
     */
    public function start($id = null)
    {
        $resource = $this->loadResource($id);

        if (!$this->validateResourceForUser($resource)) {
            throw new UnauthorizedException();
        }

        try {
            $tracking = $this->startTracking($resource);

            if ($tracking) {
                $this->set('tracking', $tracking);
                $this->set('_serialize', ['tracking']);
            }
        } catch (\InvalidArgumentException $e) {
            $this->response->statusCode(400);
            $this->set('error', $e->getMessage());
        }
    }

    /**
     * Stop method will try to stop a tacking over the target resource
     *
     * @param int $id The resource id
     * @return void Renders the tracking or the error when trying to start tracking
     */
    public function stop($id = null)
    {
        $resource = $this->loadResource($id);

        if (!$this->validateResourceForUser($resource)) {
            throw new UnauthorizedException();
        }

        try {
            $tracking = $this->stopTracking($resource);

            if ($tracking) {
                $this->set('tracking', $tracking);
                $this->set('_serialize', ['tracking']);
            }
        } catch (\InvalidArgumentException $e) {
            $this->response->statusCode(400);
            $this->set('error', $e->getMessage());
        }
    }

    /**
     * Load the target resource
     *
     * @param int $id The id of the resource
     * @return Model The found resource
     * @throws RecordNotFoundException If the resource was not found
     */
    private function loadResource($id)
    {
        return $this->{$this->modelClass}->get($id);
    }

    /**
     * Delegates the startTracking to the TrackableBehavior
     *
     * @param Model $resource The target resource
     * @return mixed The model if it was successful started otherwise false
     * @throws \InvalidArgumentException
     */
    private function startTracking($resource)
    {
        return $this->{$this->modelClass}->startTracking($resource);
    }

    /**
     * Delegates the stopTracking to the TrackableBehavior
     *
     * @param Model $resource The target resource
     * @return mixed The model if it was successful stopped otherwise false
     * @throws \InvalidArgumentException
     */
    private function stopTracking($resource)
    {
        return $this->{$this->modelClass}->stopTracking($resource);
    }

    /**
     * It will checks if the resource belongs to the current user
     *
     * @param Model $resource The resource to validate
     * @return bool True if the resource belongs to the current user otherwise false
     */
    private function validateResourceForUser($resource)
    {
        $user = $this->Auth->user();
        return $resource->get('user_id') == $user['id'];
    }
}
