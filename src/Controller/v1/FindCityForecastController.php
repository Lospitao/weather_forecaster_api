<?php

namespace App\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WeatherAPILib;

class FindCityForecastController extends AbstractController
{
    /**
     * @Route("/find/city/forecast/{city_name}", name="find_city_forecast")
     * @param $city_name
     */
    public function index($city_name): Response
    {
        $key = '33e599d03dfb49cfb55144430222101';
        $client = new WeatherAPILib\WeatherAPIClient($key);
        $aPIs = $client->getAPIs();
        try {
            $weather_forecast = $aPIs->searchAutocompleteWeather($city_name);
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
