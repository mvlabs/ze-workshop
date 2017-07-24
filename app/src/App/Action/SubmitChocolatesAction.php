<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\ChocolatesService;
use App\Domain\Service\UsersService;
use App\Domain\Value\Address;
use App\Domain\Value\ChocolateId;
use App\Domain\Value\Country;
use App\Domain\Value\Percentage;
use App\Domain\Value\Producer;
use App\Domain\Value\Quantity;
use App\Domain\Value\WrapperType;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\HttpAuthentication;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class SubmitChocolatesAction implements MiddlewareInterface
{
    /**
     * @var ChocolatesService
     */
    private $chocolatesService;

    /**
     * @var UsersService
     */
    private $usersService;

    public function __construct(
        ChocolatesService $chocolatesService,
        UsersService $usersService
    ) {
        $this->chocolatesService = $chocolatesService;
        $this->usersService = $usersService;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $data = $request->getParsedBody();

        //try {
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
            $percentage = Percentage::integer((int) $data['chocolate_percentage']);
            $wrapperType = WrapperType::get($data['chocolate_wrapper_type']);
            $quantity = Quantity::grams((int) $data['chocolate_quantity']);
            $user = $this->usersService->byUsername($request->getAttribute(HttpAuthentication::class));

            $this->chocolatesService->submit(
                $chocolateId,
                $producer,
                $description,
                $percentage,
                $wrapperType,
                $quantity,
                $user
            );
        /*} catch (InvalidCountryCodeException $e) {
            // TODO
        } catch (InvalidPercentageException $e) {
            // TODO
        } catch (InvalidWrapperTypeException $e) {
            // TODO
        } catch (NegativeQuantityException $e) {
            // TODO
        } catch (UserNotFoundException $e) {
            // TODO
        } catch (\Throwable $e) {
            // TODO
        }*/

        return new JsonResponse([]);
    }
}
