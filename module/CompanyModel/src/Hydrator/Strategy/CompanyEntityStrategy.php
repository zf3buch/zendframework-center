<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyModel\Hydrator\Strategy;

use CompanyModel\Entity\CompanyEntity;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * Class CompanyEntityStrategy
 *
 * @package CompanyModel\Hydrator\Strategy
 */
class CompanyEntityStrategy implements StrategyInterface
{
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * CompanyEntityStrategy constructor.
     *
     * @param HydratorInterface $hydrator
     */
    public function __construct(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @param integer|CompanyEntity $value
     *
     * @return mixed
     */
    public function extract($value)
    {
        if ($value instanceof CompanyEntity) {
            return $value->getId();
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param array $data
     *
     * @return mixed
     */
    public function hydrate($value, $data = [])
    {
        $companyData = [];

        foreach ($data as $key => $value) {
            if (substr($key, 0, 8) != 'company_') {
                continue;
            }

            $companyData[substr($key, 8)] = $value;
        }

        $companyEntity = new CompanyEntity();

        $this->hydrator->hydrate($companyData, $companyEntity);

        return $companyEntity;
    }
}
