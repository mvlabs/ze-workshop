<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\ChocolatesServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Value\Address;
use App\Domain\Value\ChocolateId;
use App\Domain\Value\Country;
use App\Domain\Value\Exception\InvalidPercentageException;
use App\Domain\Value\Exception\InvalidQuantityException;
use App\Domain\Value\Percentage;
use App\Domain\Value\Producer;
use App\Domain\Value\Quantity;
use App\Domain\Value\WrapperType;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\HttpAuthentication;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\JsonResponse;

final class SubmitChocolateAction implements MiddlewareInterface
{
    /**
     * @var ChocolatesServiceInterface
     */
    private $chocolatesService;

    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    public function __construct(
        ChocolatesServiceInterface $chocolatesService,
        UsersServiceInterface $usersService
    ) {
        $this->chocolatesService = $chocolatesService;
        $this->usersService = $usersService;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (
            !isset($data['producer_name']) ||
            !isset($data['producer_street']) ||
            !isset($data['producer_number']) ||
            !isset($data['producer_zip_code']) ||
            !isset($data['producer_city']) ||
            !isset($data['producer_region']) ||
            !isset($data['producer_country']) ||
            !isset($data['chocolate_description']) ||
            !isset($data['chocolate_percentage']) ||
            !isset($data['chocolate_wrapper_type']) ||
            !isset($data['chocolate_quantity'])
        ) {
            return (new EmptyResponse())->withStatus(400);
        }

        $chocolateId = ChocolateId::new();
        $producer = Producer::fromNameAndAddress(
            $data['producer_name'],
            Address::fromStreetNumberZipCodeCityRegionCountry(
                $data['producer_street'],
                $data['producer_number'],
                $data['producer_zip_code'],
                $data['producer_city'],
                $data['producer_region'],
                Country::fromStringCode($data['producer_country'])
            )
        );
        $description = $data['chocolate_description'];

        if (null === $data['chocolate_percentage']) {
            throw InvalidPercentageException::nonIntegerValue($data['chocolate_percentage']);
        }

        $percentage = Percentage::integer((int) $data['chocolate_percentage']);
        $wrapperType = WrapperType::get($data['chocolate_wrapper_type']);

        if (null === $data['chocolate_quantity']) {
            throw InvalidQuantityException::nonIntegerValue($data['chocolate_quantity']);
        }

        $quantity = Quantity::grams((int) $data['chocolate_quantity']);
        $user = $this->usersService->getByUsername('user'); // TODO: authenticate user

        $this->chocolatesService->submit(
            $chocolateId,
            $producer,
            $description,
            $percentage,
            $wrapperType,
            $quantity,
            $user
        );

        return new JsonResponse([]);
    }
}
