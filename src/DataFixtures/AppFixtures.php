<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Region;
use App\Entity\Room;

class AppFixtures extends Fixture
{
    public const IDF_REGION_REFERENCE = 'idf-region';
    public const RA_REGION_REFERENCE = 'ra-region';
    
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Ile de France");
        $region->setPresentation("La région française capitale");
        $manager->persist($region);
        
        $manager->flush();
        // Une fois l'instance de Region sauvée en base de données,
        // elle dispose d'un identifiant généré par Doctrine, et peut
        // donc être sauvegardée comme future référence.
        $this->addReference(self::IDF_REGION_REFERENCE, $region);
        
        // $product = new Product();
        // $manager->persist($product);
        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Rhone Alpes");
        $region->setPresentation("La plus belle région de France");
        $manager->persist($region);
        
        $manager->flush();
        // Une fois l'instance de Region sauvée en base de données,
        // elle dispose d'un identifiant généré par Doctrine, et peut
        // donc être sauvegardée comme future référence.
        $this->addReference(self::RA_REGION_REFERENCE, $region);
        // ...
        
        $room = new Room();
        $room->setSummary("Beau poulailler ancien à Évry");
        $room->setDescription("très joli espace sur paille");
        $room->setCapacity("3");
        $room->setSuperficy("10m²");
        $room->setPrice("100");
        $room->setAddress("36RM");
        //$room->addRegion($region);
        // On peut plutôt faire une référence explicite à la référence
        // enregistrée précédamment, ce qui permet d'éviter de se
        // tromper d'instance de Region :
        $room->addRegion($this->getReference(self::IDF_REGION_REFERENCE));
        $manager->persist($room);
        
        $manager->flush();
        
        $room = new Room();
        $room->setSummary("Jolie Villa aux monts d'or");
        $room->setDescription("très jolie vue sur Lyon et les Alpes");
        $room->setCapacity("9");
        $room->setSuperficy("537m²");
        $room->setPrice("15000");
        $room->setAddress("36 Chemin de grand champ");
        //$room->addRegion($region);
        // On peut plutôt faire une référence explicite à la référence
        // enregistrée précédamment, ce qui permet d'éviter de se
        // tromper d'instance de Region :
        $room->addRegion($this->getReference(self::RA_REGION_REFERENCE));
        $manager->persist($room);
        
        $manager->flush();
    }
}
