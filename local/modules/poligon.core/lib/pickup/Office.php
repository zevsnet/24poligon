<?php


namespace Poligon\Core\Pickup;


use SB\Site\Repository\CatalogStoreRepository;

class Office extends PickupList
{
    private const USER_TYPE_ID_USER = 1;
    private const USER_TYPE_ID_COMPANY = 2;
    private const TEMP_COMPANY_STORE_ID = 3;

    public function getPickupList()
    {
        $userTypeId = $this->getOrder()->getPersonTypeId();
//        if ($userTypeId === self::USER_TYPE_ID_USER) {
            return $this->getUserOfficeList();
//        } else {
//            return $this->getCompanyOfficeList();
//        }
    }

    private function getUserOfficeList()
    {
        /** @var CatalogStoreRepository $storeRepository */
        $storeRepository = new CatalogStoreRepository();
        $stores = $storeRepository->getPickup();
        $result = [];

        $i = 0;
        foreach ($stores as $index => $store) {
            $entity             = $this->entityToArray($store);
            $entity['selected'] = $i === 0;
            $result[]           = $entity;
            $i++;
        }

        return $result;
    }

    /**
     * временный метод подмены для юр лиц
     */
    private function getCompanyOfficeList()
    {
        /** @var CatalogStoreRepository $storeRepository */
        $storeRepository = app(CatalogStoreRepository::class);

        $store                 = $storeRepository->getById(self::TEMP_COMPANY_STORE_ID);
        $entity                = $this->entityToArray($store);
        $entity['description'] = 'Пункт самовывоза для юридических лиц';
        $entity['selected']    = true;
        return [
            $entity
        ];
    }

    private function entityToArray($entity)
    {
        return [
            'id' => strval($entity->getId()),
            'title' => $entity->getTitle(),
            'description' => $entity->getAddress(),
            'zip' => null,
            'lat' => (double)$entity->getGpsN(),
            'lon' => (double)$entity->getGpsS(),
            'selected' => false
        ];
    }
}
