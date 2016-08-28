<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertRest\Controller;

use AdvertModel\Entity\AdvertEntity;
use AdvertModel\Hydrator\AdvertHydrator;
use AdvertModel\InputFilter\AdvertInputFilter;
use AdvertModel\Repository\AdvertRepositoryInterface;
use Zend\Http\Header\Allow;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class RestController
 *
 * @package AdvertRest\Controller
 */
class RestController extends AbstractRestfulController
{
    /**
     * @var AdvertRepositoryInterface
     */
    private $advertRepository;

    /**
     * @var AdvertHydrator
     */
    private $advertHydrator;

    /**
     * @var AdvertInputFilter
     */
    private $advertInputFilter;

    /**
     * @param AdvertRepositoryInterface $advertRepository
     */
    public function setAdvertRepository(
        AdvertRepositoryInterface $advertRepository
    ) {
        $this->advertRepository = $advertRepository;
    }

    /**
     * @param AdvertHydrator $advertHydrator
     */
    public function setAdvertHydrator(AdvertHydrator $advertHydrator)
    {
        $this->advertHydrator = $advertHydrator;
    }

    /**
     * @param AdvertInputFilter $advertInputFilter
     */
    public function setAdvertInputFilter(
        AdvertInputFilter $advertInputFilter
    ) {
        $this->advertInputFilter = $advertInputFilter;
    }

    /**
     * @return JsonModel
     */
    public function getList()
    {
        $type = $this->params()->fromRoute('type', 'job');

        $advertPaginator = $this->advertRepository->getAdvertsByPage(
            $type, true, 1, 99
        );

        $advertList = [];

        /** @var AdvertEntity $advert */
        foreach ($advertPaginator as $advert) {
            $advertList[$advert->getId()] = $this->advertHydrator->extract(
                $advert
            );
        }

        return new JsonModel($advertList);
    }

    /**
     * @param mixed $id
     *
     * @return JsonModel
     */
    public function get($id)
    {
        $advert = $this->advertRepository->getSingleAdvertById($id);

        if (!$advert) {
            return new JsonModel(['error' => 'Advert not found']);
        }

        return new JsonModel($this->advertHydrator->extract($advert));
    }

    /**
     * @param mixed $data
     *
     * @return JsonModel
     */
    public function create($data)
    {
        $this->advertInputFilter->setData($data);

        if (!$this->advertInputFilter->isValid()) {
            return new JsonModel(
                [
                    'error'    => 'Invalid data',
                    'messages' => $this->advertInputFilter->getMessages(),
                ]
            );
        }

        $advert = $this->advertRepository->createAdvertFromData(
            $this->advertInputFilter->getValues()
        );

        $result = $this->advertRepository->saveAdvert($advert);

        if (!$result) {
            return new JsonModel(['error' => 'Advert not saved']);
        }

        return new JsonModel($this->advertHydrator->extract($advert));
    }

    /**
     * @param mixed $id
     * @param mixed $data
     *
     * @return JsonModel
     */
    public function update($id, $data)
    {
        $advert = $this->advertRepository->getSingleAdvertById($id);

        if (!$advert) {
            return new JsonModel(['error' => 'Advert not found']);
        }

        $this->advertInputFilter->setValidationGroup(
            ['title', 'text', 'location']
        );
        $this->advertInputFilter->setData($data);

        if (!$this->advertInputFilter->isValid()) {
            return new JsonModel(
                [
                    'error'    => 'Invalid data',
                    'messages' => $this->advertInputFilter->getMessages(),
                ]
            );
        }

        $advert->setTitle($this->advertInputFilter->getValue('title'));
        $advert->setText($this->advertInputFilter->getValue('text'));
        $advert->setLocation(
            $this->advertInputFilter->getValue('location')
        );
        $advert->update();

        $result = $this->advertRepository->saveAdvert($advert);

        if (!$result) {
            return new JsonModel(['error' => 'Advert not saved']);
        }

        return new JsonModel($this->advertHydrator->extract($advert));
    }

    /**
     * @param mixed $id
     *
     * @return JsonModel
     */
    public function delete($id)
    {
        $advert = $this->advertRepository->getSingleAdvertById($id);

        if (!$advert) {
            return new JsonModel(['error' => 'Advert not found']);
        }

        $this->advertRepository->deleteAdvert($advert);

        return new JsonModel(['success' => 'Advert was deleted']);
    }

    /**
     * @return JsonModel
     */
    public function options()
    {
        /** @var Response $response */
        $response = $this->response;
        $response->getHeaders()->addHeader(
            new Allow(['GET', 'POST', 'PUT', 'DELETE'])
        );

        return new JsonModel();
    }

    /**
     * @param mixed|null|null $id
     *
     * @return JsonModel
     */
    public function head($id = null)
    {
        return new JsonModel(parent::head($id));
    }

    /**
     * @param $id
     * @param $data
     *
     * @return JsonModel
     */
    public function patch($id, $data)
    {
        return new JsonModel(parent::patch($id, $data));
    }

    /**
     * @param mixed $data
     *
     * @return JsonModel
     */
    public function replaceList($data)
    {
        return new JsonModel(parent::replaceList($data));
    }

    /**
     * @param mixed $data
     *
     * @return JsonModel
     */
    public function patchList($data)
    {
        return new JsonModel(parent::patchList($data));
    }

    /**
     * @param $data
     *
     * @return JsonModel
     */
    public function deleteList($data)
    {
        return new JsonModel(parent::deleteList($data));
    }
}
