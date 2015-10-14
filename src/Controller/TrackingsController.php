<?php
namespace App\Controller;

use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Inflector;
use Cake\Event\Event;

/**
 * Trackings Controller
 *
 * @property \App\Model\Table\TrackingsTable $Trackings
 */
class TrackingsController extends AppController
{

    /**
     * Add method
     *
     * @return void
     */
    public function add()
    {
        $this->request->allowMethod(['post']);

        $this->loadAndValidateTrackable();

        $tracking = $this->Trackings->newEntity();
        $tracking = $this->Trackings->patchEntity($tracking, $this->request->data);
        $tracking->set('trackable_id', $this->trackable->id);
        $tracking->set('trackable_type', $this->getTrackableClass());

        if ($this->Trackings->save($tracking)) {
            $this->response->statusCode(201);
            $this->set(compact('tracking'));
            $this->set('_serialize', ['tracking']);
        } else {
            $this->response->statusCode(400);
            $this->set('errors', $tracking->errors());
            $this->set('_serialize', ['errors']);
        }
    }

    /**
     * Edit method
     *
     * @return void
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['post', 'put', 'patch']);

        $this->loadAndValidateTrackable();
        $tracking = $this->loadAndValidateTracking($id);

        $tracking = $this->Trackings->patchEntity($tracking, $this->request->data);

        if ($this->Trackings->save($tracking)) {
            $this->set('tracking', $tracking);
            $this->set('_serialize', ['tracking']);
        } else {
            $this->response->statusCode(400);
            $this->set('errors', $tracking->errors());
            $this->set('_serialize', ['errors']);
        }
    }

    /**
     * Add method
     *
     * @return void
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $this->loadAndValidateTrackable();
        $tracking = $this->loadAndValidateTracking($id);

        if (!$this->Trackings->delete($tracking)) {
            $this->response->statusCode(400);
        }
    }
    
    /**
     * It will load and validate the trackable resource for user
     *
     * @return void
     * @throws UnauthorizedException
     */
    private function loadAndValidateTrackable()
    {
        $this->trackable = $this->loadTrackable();
        $user = $this->Auth->user();

        if ($this->trackable->user_id != $user['id']) {
            throw new UnauthorizedException();
        }
    }

    /**
     * Loads and validate a tracking instance
     *
     * @param int $id The id of the tracking
     * @return Tracking The loaded tracking
     * @throws UnauthorizedException if the tracking doesn't belong to the current user
     */
    private function loadAndValidateTracking($id)
    {
        $tracking = $this->Trackings->get($id);

        if ($tracking->trackable_id != $this->trackable->id || $tracking->trackable_type != $this->getTrackableClass()) {
            throw new UnauthorizedException();
        }

        return $tracking;
    }

    /**
     * Loads the trackable resource based on request
     *
     * @return Model The trackable resource
     */
    private function loadTrackable()
    {
        try {
            $modelClass = $this->getTrackableClass();
            $Trackable = $this->loadModel($modelClass);
            $trackable = $Trackable->get($this->request->params['trackable_id']);

            return $trackable;
        } catch (\Cake\Database\Exception $e) {
            throw new NotFoundException();
        }


    }

    /**
     * Get the trackable class based on request
     *
     * @return string Trackable class
     */
    private function getTrackableClass()
    {
        return Inflector::camelize($this->request->params['trackable']);
    }
}
