<?php
namespace App\Test\TestCase\Controller;

use Cake\Utility\Security;

trait HelperTrait
{

    /**
     * getToken function
     *
     * @return string The token to be used in the request
     */
    private function getToken()
    {
        $userId = $this->Users->find()->first()->id;

        return \JWT::encode(
            [
                'id' => $userId
            ],
            Security::salt()
        );
    }

    private function decodedResponse()
    {
        return json_decode($this->_response->body(), true);
    }

    private function getExpectedResponse($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
