<?php

namespace App\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WeatherAPILib;

class SuggestedCitiesController extends AbstractController
{
    /**
     * @Route("/suggested_cities/{cityName}", name="find_city_forecast")
     * @param $cityName
     * @return mixed
     */
    public function index($cityName): Response
    {
        $key = '33e599d03dfb49cfb55144430222101';
        $client = new WeatherAPILib\WeatherAPIClient($key);
        $aPIs = $client->getAPIs();
        $suggestions = [];
        try {
            $suggestedCities = $aPIs->searchAutocompleteWeather($cityName);
            foreach ($suggestedCities as $city) {
                $suggestions[] = $city->name;
            }
            return new JsonResponse($suggestions);
        } catch (\WeatherAPILib\APIException $exception) {
            return $this->createJsonResponseWithError($exception);
        } catch (\Exception $exception) {
            return $this->createErrorResponse($exception);
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

    private function createErrorResponse(\Exception $exception)
    {
        return new JsonResponse([
            'code' =>$exception->getCode(),
            'message' =>$exception->getMessage(),
        ], Response::HTTP_BAD_REQUEST);

    }

}
