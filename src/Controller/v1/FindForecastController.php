<?php

namespace App\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WeatherAPILib;

class FindForecastController extends AbstractController
{
    /**
     * @Route("/find_forecast/{city_name}", name="find_forecast")
     * @param $city_name
     * @return mixed
     */
    public function index($city_name): Response
    {
        $key = '33e599d03dfb49cfb55144430222101';
        $client = new WeatherAPILib\WeatherAPIClient($key);
        $aPIs = $client->getAPIs();
        try {
            $weather_forecast = $aPIs->getForecastWeather($city_name, 10);
            return new JsonResponse($weather_forecast);
        } catch (\WeatherAPILib\APIException $exception) {
            return $this->createJsonResponseWithError($exception);
        }
    }
    private function createJsonResponseWithError($exception)
    {
        return new JsonResponse([
            'context' =>$exception->getContext(),
            'responseCode' => $exception->getResponseCode(),
            'reason' => $exception->getReason(),

        ]);
    }
}
