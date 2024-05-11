<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Adress;
use App\Entity\Category;
use App\Entity\Material;
use App\Entity\Product;
use App\Entity\OrderProduct;
use App\Entity\Order;
use App\Entity\Images;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadCategory($manager);
        $this->loadMaterial($manager);
        $this->loadProduct($manager);
        $this->loadAdresses($manager);
        $this->loadOrderProduct($manager);
        $this->loadOrders($manager);

        $manager->flush();
    }


    private function loadUsers(ObjectManager $manager): void
    {
        foreach($this->getUserData() as [$name, $last_name, $email, $role, $phoneNumber, $password, $birthday]) {
            $user = new User();
            $user->setName($name);
            $user->setLastName($last_name);
            $user->setEmail($email);
            $user->setRoles($role);
            $user->setPhoneNumber($phoneNumber);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setBirthday($birthday);
            $manager->persist($user);
            $this->addReference($email, $user);
        }

        $manager->flush();
    }

    private function getUserData(): array 
    {
        return [
            ['Remi','Administrator','admin@symfony.com',['ROLE_ADMIN'],'0645789465','admin', new \DateTime('1980-01-01')],
            ['Doe','Administrator','doe@symfony.com',['ROLE_ADMIN'],'0645789465','admin', new \DateTime('1970-01-01')],
            ['John','Lennon','john@symfony.com',[''],'0645789465','user', new \DateTime('1990-01-01')],
            ['Jane','Finn','jane@symfony.com',[''],'0645789465','user', new \DateTime('2000-01-01')],
            ['Cyril','Le Bucheron','cyril@symfony.com',[''],'0645789465','user', new \DateTime('1995-01-01')],
            ['Marion','Fabregas','marion@symfony.com',[''],'0645789465','user', new \DateTime('1992-01-01')]
        ];
    }

    private function loadAdresses(ObjectManager $manager): void
    {
        foreach($this->getAdressData() as [$email, $city, $region, $country, $zipCode,$streetAdress, $adressName, $adressReference]) {
            $adress = new Adress();
            $adress->setCity($city);
            $adress->setRegion($region);
            $adress->setCountry($country);
            $adress->setZipCode($zipCode);
            $adress->setAdress($streetAdress);
            $adress->setName($adressName);
            $user = $this->getReference($email);
            $adress->setIdUser($user);
            $manager->persist($adress);
            $this->addReference($adressReference, $adress);
        }

        $manager->flush();
    }

    private function getAdressData(): array
    {
        return [
            ['john@symfony.com','Paris','Ile-de-France','France',75000,'1 rue de Paris','Maison',"JohnAdress"],
            ['jane@symfony.com','London','England', 'United Kingdom', 'SW1A 1AA','1 London Street','Maison',"JaneAdress"],
            ['cyril@symfony.com','Bordeaux','Nouvelle-Aquitaine','France',33000,'1 rue de Bordeaux','Maison','CyrilAdress'],
            ['marion@symfony.com','Berlin','Berlin','Germany',10115,'1 Berliner Strasse','Maison','MarionAdress']
        ];
    }

    private function loadCategory(ObjectManager $manager): void
    {
        foreach($this->getCategoriesData() as [$name, $imagePath, $description]) {
            $category = new Category();
            $category->setName($name);

            // Ajoutez l'image à la catégorie
            $image = new Images();
            $image->setFilePath($imagePath);
            $manager->persist($image);
            $category->setImage($image);

            $category->setDescription($description);
            $manager->persist($category);
            $this->addReference($name, $category);
        }

        $manager->flush();
    }



    private function getCategoriesData(): array
    {
        return [
            //['Armoires penderie et dressing','penderie_category.avif','Cette catégorie regroupe tous les types d\'armoires penderie et dressing'],
            //['Meubles de salle à manger','meublemanger_category.avif','Cette catégorie regroupe tous les types de meubles de salle à manger'],
            //['Commodes et caissons à tiroir','commode_category.avif','Cette catégorie regroupe tous les types de commodes et caissons à tiroir'],
            ['Tables','table_category.avif','Cette catégorie regroupe tous les types de tables'],
            ['Chaises','chaise_category.avif','Cette catégorie regroupe tous les types de chaises'],
            ['Bureaux','bureau_category.avif','Cette catégorie regroupe tous les types de bureaux'],
            ['Buffets et consoles','buffet_category.avif','Cette catégorie regroupe tous les types de buffets et consoles'],
            ['Meubles TV','meubletv_category.avif','Cette catégorie regroupe tous les types de meubles TV'],
            ['Canapés','canape_category.avif','Cette catégorie regroupe tous les types de canapés']
        ]; 
    }

    private function loadMaterial(ObjectManager $manager): void {
        foreach($this->getMaterialsData() as [$name]) {
            $material = new Material();
            $material->setName($name);
            $manager->persist($material);
            $this->addReference($name, $material);
        }
    
        $manager->flush();
    }

    private function getMaterialsData(): array {
        return [
            ["Acacia massif"],
            ["Acier"],
            ["Adhésif"],
            ["Bouleau"],
            ["Bouleau massif"],
            ["Bois chêne"],
            ["Carton homogène recyclé"],
            ["Chêne massif"],
            ['Feuille de mélamine'],
            ["Fibres de bois/plastique"],
            ["Film plastique"],
            ["Hêtre massif"],
            ["Lamibois"],
            ["Panneau de fibres de bois"],
            ["Panneau de particules"],
            ["Papier métallisé"],
            ["Pin massif"],
            ["Placage bouleau"],
            ["Placage chêne"],
            ["Placage chêne épais"],
            ["Placage frêne"],
            ["Placage hêtre épais"],
            ["Placage lamellé-collé"],
            ["Placage noyer"],
            ["Plastique"],
            ["Polyester"],
            ["Polypropylène"],
            ["Tissu 100% polyester"],
            ["Vernis acrylique incolore"],
            ["Vernis acrylique teinté"],
            ["Vernis incolore"],
            ["Verre trempé"]
        ];
    }
    
    
    

    private function loadProduct(ObjectManager $manager): void
    {
        foreach($this->getProductsData() as [$name, $price, $imagePaths, $description, $details, $categoryName, $stock, $materials]) {
            $product = new Product();
    
            $category = $this->getReference($categoryName);
            $product->setName($name);
            $product->setPrice($price);
            
            foreach ($imagePaths as $imagePath) {
                $image = new Images();
                $image->setFilePath($imagePath);
                $manager->persist($image);
                $product->addImage($image);
            }
            
            $product->setDescription($description);
            $product->setDetails($details);
            $product->addCategory($category);
            $product->setStock($stock);
            foreach ($materials as $materialName) {
                $material = $this->getReference($materialName);
                $product->addMaterial($material);
            }
            $product->setAddedDate(new \DateTime());
            $manager->persist($product);
            $this->addReference($name, $product);
        }
    
        $manager->flush();
    }

    private function getProductsData(): array 
    {
        return [
            // 8 chaises + 1 épuisée
            ['LES EKEDALEN',59.99,['ekedalen-chaise.avif','ekedalen-chaise2.avif','ekedalen-chaise3.avif'],"Cette chaise en bois confortable est du plus bel effet dans la salle à manger. Profitez de vos repas en toute sérénité grâce à son dossier à barreaux haut et incurvé, ainsi qu'à son assise moelleuse, revêtue d'une housse amovible et lavable.",'Chaise, motif chêne/Orrsta gris clair','Chaises', true,['Hêtre massif','Adhésif', "Panneau de fibres de bois"]], // hêtre massif, Adhésif, Panneau de fibres de bois
            ['BERGMUND', 89.99, ['bergmund-chaise.avif','bergmund-chaise2.jpg','bergmund-chaise3.avif'],"Grâce à nos recherches et au développement de nouvelles techniques, la chaise vous offre un confort optimal. Pour changer son style, il suffit de remplacer la housse grise par une housse d'une autre couleur ou d'un autre modèle.", 'Chaise, noir/Gunnared gris moyen','Chaises', true,['Acier','Tissu 100% polyester']], // acier,  Tissu 100 Polyester
            ['KARLPETTER', 49.99, ['karlpetter-chaise.avif','karlpetter-chaise2.avif','karlpetter-chaise3.avif'],"Cette chaise rembourrée est particulièrement confortable pour diverses activités : travailler, bricoler ou manger.", 'Chaise, Gunnared vert clair/Sefast noir', 'Chaises', true,['Tissu 100% polyester','Acier', 'Placage lamellé-collé']], // Placage bois lamellé-collé, acier,  Tissu 100 polyester
            ['STEFAN',29.99,['stefan-chaise.avif','stefan-chaise2.avif','stefan-chaise3.avif'],"La structure en bois de cette chaise est particulièrement solide et peut résister aux assauts de la vie quotidienne.",'Chaise, brun noir','Chaises', true, ['Pin massif', 'Vernis incolore']], // Pin massif, Vernis incolore
            ['ODGER', 89.99,['odger-chaise.avif','odger-chaise2.avif','odger-chaise3.webp'],"La coque moulée offre un grand confort dont vous pourrez bénéficier après un montage en 3 clics seulement. Pour assurer sa solidité et son esthétique du bois a été mélangé à du plastique gris charbon, avec un léger effet paillettes.", 'Chaise, anthracite', 'Chaises', true,['Panneau de fibres de bois','Plastique']], // Fibres de bois/plastique
            ['ADDE', 15.99, ['adde-chaise.avif','adde-chaise2.avif','adde-chaise3.avif'],"Les chaises peuvent être empilées pour libérer de l'espace quand elles ne servent pas.",'Chaise, noir','Chaises',true,['Acier']], // acier, revêtement époxy/polyester (poudre)
            ['MÅRENÄS', 119, ['marenaes-chaise.avif','marenaes-chaise2.avif','marenaes-chaise3.avif'],"Un agréable confort avec assise et dossier rembourrés et housses amovibles et lavables.",'Chaise à accoudoirs, noir/Gunnared beige','Chaises', true,['Adhésif','Polypropylène','Tissu 100% polyester','Acier']], // Adhésif, 100% polypropylène, Tissu 100 % polyester, acier
            ['LIDÅS', 34.99,['lidas-chaise.avif','lidas-chaise2.jpg','lidas-chaise3.avif'],"Vous serez confortablement assis grâce à l'assise creusée et à la souplesse reposante du dossier. Le matériau se nettoie en un clin d'œil.",'Chaise, blanc/Sefast blanc','Chaises', true,['Polypropylène','Acier']], // Polypropylène renforcé, acier
            ['LES SANDSBERG', 34.99,['sandsberg-chaise.avif','sandsberg-chaise2.avif','sandsberg-chaise3.avif'],"Cette chaise combine l'aspect chaleureux du bois avec un métal robuste pour un design élancé agréable à l'œil même dans les petits espaces. Le dossier à 2 lattes apporte du confort pour plus de détente.",'Chaise, noir/teinté brun', 'Chaises', false,['Acier','Bouleau']], // acier, contreplaqué de bouleau
            // 8 tables + 1 épuisée
            ['LINNMON / ADILS', 34.99,['linnmon-adils-table.avif','linnmon-adils-table2.avif','linnmon-adils-table3.avif'],"Trous pré-percés pour le montage de pieds. Grâce à ses pieds réglables, la table reste stable même sur des surfaces irrégulières.",'Table, blanc, 100x60 cm', 'Tables', true,['Panneau de fibres de bois','Acier']], // panneau de fibre de bois, acier 
            ['MÖRBYLÅNGA', 499, ['moerbylanga-table.avif','moerbylanga-table2.avif','moerbylanga-table3.avif'],"Le plateau en chêne renforce le style brut de la table et les détails tels que le plateau surélevé et les jointures en queue d'aronde lui donnent un look artisanal. Cette table de caractère peut accueillir de nombreux parents et amis.", 'Table, plaqué chêne teinté brun', 'Tables', true,['Panneau de particules','Placage chêne','Vernis acrylique teinté','Placage chêne épais']], // Panneau de particules, placage chêne, Vernis acrylique teinté, Placage de chêne épais
            ['SKANSNÄS', 599,['skansnaes-table.avif','skansnaes-table2.avif','skansnaes-table3.avif'],"Le style moderne de la table SKANSNÄS s'inspire du design scandinave traditionnel. Avec sa structure robuste, son plateau en placage épais et son système de rallonges, cette élégante table en bois pourra être utilisée pendant longtemps.", 'Table extensible, brun hêtre/plaqué, 115/170 cm', 'Tables', true,['Panneau de particules','Hêtre massif','Adhésif','Placage hêtre épais','Vernis acrylique teinté']], // Panneau de particules, hêtre massif, Adhésif, placage de hêtre épais, Vernis acrylique teinté
            ['SKOGSTA',599,['skogsta-table.avif', 'skogsta-table2.avif', 'skogsta-table3.avif'],"Chaque table est unique, avec son grain au motif particulier et ses teintes différentes qui font tout le charme du bois.", 'Table, acacia, 235x100 cm', 'Tables', true,['Acacia massif','Vernis acrylique incolore','Vernis incolore']],// acacia massif, vernis acrylique incolore, vernis incolore
            ['TARSELE', 499, ['tarsele-table.avif','tarsele-table2.avif','tarsele-table3.avif','tarsele-table4.avif'],"Cette table extensible est un mélange parfait de placage en chêne et de métal solide. Elle s'inspire du design industriel des années 50. Le plateau cache aussi une rallonge, idéale lorsque vous recevez des amis.",'Table extensible, plaqué chêne/noir, 150/200x80 cm', 'Tables', true,['Panneau de fibres de bois','Placage chêne','Vernis acrylique teinté','Papier métallisé','Acier']], // Panneau de fibres de bois, placage chêne, Vernis acrylique teinté, papier métallisé, acier
            ['LISABO', 199,['lisabo-table.avif','lisabo-table2.avif','lisabo-table3.avif'],"Une table simple et élégante, à l'aspect de bois naturel chaleureux. La fabrication solide est stable et le montage se fait en toute facilité.",'Table, plaqué frêne, 140x78 cm','Tables', true,['Placage frêne','Placage bouleau','Bouleau massif','Panneau de particules','Vernis acrylique teinté','Panneau de fibres de bois']], //placage frêne, placage bouleau, bouleau massif, Panneau de particules, Vernis acrylique teinté, Panneau de fibres de bois
            ['LA EKEDALEN',249,['ekedalen-table.avif','ekedalen-table2.avif','ekedalen-table3.avif'],"Une table de salle à manger solide et idéale pour de grands repas. Il est possible de la déplier seul. Les quatre pieds en coin de table ne gênent pas et permettent d'accueillir de nombreuses chaises.",'Table extensible, chêne, 120/180x80 cm','Tables', true,['Panneau de particules','Placage chêne','Vernis acrylique incolore','Chêne massif','Bouleau massif']], // Panneau de particules, placage chêne, vernis acrylique incolore, Chêne massif, bouleau massif
            ['PINNTORP',129,['pinntorp-table.avif','pinntorp-table2.avif','pinntorp-table3.avif'],"Cette table en bois peut largement accueillir 4 personnes. Inspirée du mobilier suédois traditionnel, son design simple, chaleureux et plein de caractère en fait une table idéale pour s'asseoir et partager de bons moments.",'Table, teinté brun clair/teinté blanc, 125x75 cm','Tables', true,['Pin massif','Adhésif','Vernis acrylique incolore','Vernis incolore']], // Pin massif, Adhésif, vernis acrylique incolore, vernis incolore
            ['LA SANDSBERG', 48.99,['sandsberg-table.avif','sandsberg-table2.avif','sandsberg-table3.avif'],"Cette table pour 4 personnes combine l'aspect chaleureux du bois avec un métal robuste pour un design élancé agréable à l'œil même dans les petits espaces. Ajoutez-y la chaise SANDSBERG pour créer un look accueillant et coordonné.",'Table, noir, 110x67 cm','Tables', false,['Panneau de particules','Feuille de mélamine','Acier']], // Panneau de particules, Feuille de mélamine, acier
            //8 bureaux + 1 épuisé
            ['LAGKAPTEN / ALEX', 133.99, ['lagkapten-alex.avif','lagkapten-alex2.avif','lagkapten-alex3.avif'],"Un petit espace ne veut pas dire qu'on ne peut pas étudier ou travailler confortablement. Ce bureau ne prend pas beaucoup de place au sol et dispose d'un caisson de tiroirs pour ranger toutes vos affaires.", 'Bureau, effet chêne blanchi/blanc, 120x60 cm', 'Bureaux', true,['Chêne massif','Acier']], // chêne massif, acier
            ['KALLAX', 49.99, ['kallax-bureau.avif', 'kallax-bureau2.avif','kallax-bureau3.avif'],"Un bureau peu profond, au style intemporel, dont le design astucieux permet de cacher les câbles sous le plateau. Facile à personnaliser en ajoutant des portes, des boîtes et d'autres accessoires de la série KALLAX. Vous serez calé avec KALLAX.", 'Bureau, blanc', 'Bureaux', true,['Panneau de fibres de bois']], // panneau de fibres de bois
            ['TONSTAD', 229, ['tonstad-bureau.avif','tonstad-bureau2.jpg','tonstad-bureau3.jpg' ],"Vous pouvez vous installer à ce bureau blanc pour y réaliser vos loisirs créatifs ou pour partager un repas. La série TONSTAD se caractérise par son design classique et sa finition blanc cassé, qui lui donnent un véritable aspect de qualité.", 'Bureau, plaqué chêne, 140x75 cm', 'Bureaux', true,['Chêne massif']], // chêne massif
            ['MICKE', 119, ['micke-bureau.avif','micke-bureau2.avif','micke-bureau3.avif'],"Combinez votre bureau au design simple et épuré à des caissons à tiroirs de la série MICKE, pour agrandir votre espace de travail. Le passage de câbles à l’arrière est idéal pour cacher les prises et câbles encombrants.",'Bureau, blanc, 142x50 cm', 'Bureaux', true,['Panneau de fibres de bois']], // panneau de fibres de bois
            ['LAGKAPTEN / ADILS', 49.99,['lagkapten-adils-bureau.avif','lagkapten-adils-bureau2.avif', 'lagkapten-adils-bureau3.jpg'],"Grâce à ses pieds réglables, la table reste stable même sur des surfaces irrégulières. Des matériaux solides et légers et une technique de fabrication qui utilise moins de matières premières.",'Bureau, effet chêne blanchi/blanc, 140x60 cm','Bureaux',true,['Panneau de fibres de bois','Acier']], // panneau de fibres de bois, acier
            ['MALM', 199,['malm-bureau.avif','malm-bureau2.avif','malm-bureau3.avif'],"Un design épuré qui s'apprécie sous tous les angles : placez le bureau au milieu de la pièce ou contre un mur. Les câbles sont bien cachés à l'intérieur. Installez la tablette coulissante à gauche ou à droite.",'Bureau avec tablette coulissante, blanc, 151x65 cm','Bureaux', true,['Panneau de fibres de bois','Panneau de particules']], // Panneau de particules, Panneau de fibres de bois
            ['TORALD',24.99,['torald-bureau.avif','torald-bureau2.avif','torald-bureau3.jpg'],"Besoin d'un petit coin bureau ? Alors TORALD est fait pour vous ! Il ne prend pas beaucoup de place dans la pièce, mais est très pratique pour avoir un endroit où étudier, dessiner et travailler.",'Bureau, blanc, 65x40 cm', 'Bureaux', true,['Panneau de particules','Feuille de mélamine','Plastique']], // Panneau de particules, Feuille de mélamine, Bord en plastique
            ['TROTTEN', 149,['trotten-bureau.avif','trotten-bureau2.avif','trotten-bureau3.avif'],"Ce bureau solide est pensé pour résister à un usage intensif et aux taches de café pendant de nombreuses années. La forme en A des pieds permet d'utiliser tout l'espace sous le plateau du bureau pour y glisser votre chaise ou un rangement.",'Bureau, blanc, 160x80 cm','Bureaux', true,['Panneau de particules','Feuille de mélamine','Plastique','Acier']], // Panneau de particules, Feuille de mélamine, Bord en plastique, Acier
            ['BEKANT', 279, ['bekant-bureau.avif','bekant-bureau2.avif','bekant-bureau3.avif'],"Ce bureau solide est pensé pour résister à un usage intensif et aux taches de café pendant de nombreuses années. Les câbles restent parfaitement cachés en dessous grâce à une solution de rangement astucieuse.",'Bureau, blanc, 160x80 cm','Bureaux', false,['Panneau de particules','Feuille de mélamine','Plastique','Acier']], // Panneau de particules, Feuille de mélamine, Bord en plastique, Acier
            // 8 consoles et buffets + 1 épuisé
            ['BESTÅ',390,['besta.avif','besta2.avif','besta3.avif'],"Cette commode permet de ranger des affaires et d'avoir une surface pour exposer des objets ou poser des plats de service pendant les repas.",'Combinaison rangement portes, brun noir/Hedeviken/Stubbarp plaqué chêne, 180x42x74 cm','Buffets et consoles', true,["Panneau de fibres de bois"]], //fibres de bois
            ['VIHALS',149,['vihals-buffet.avif','vihals-buffet2.avif','vihals-buffet3.avif'],"La série de rangement VIHALS se compose de meubles permettant d'obtenir un style uniforme à travers toutes les pièces de la maison. Le mélange de tiroirs et de tablettes permet de mettre de l'ordre dans le salon, le couloir ou n'importe quelle pièce.",'Buffet, blanc, 140x37x75 cm','Buffets et consoles', true,['Panneau de particules','Panneau de fibres de bois']], //Panneau de particules, fibres de bois
            ['SKRUVBY', 149,['skruvby-buffet.avif','skruvby-buffet2.avif','skruvby-buffet3.avif'],"La série SKRUVBY propose des éléments assortis de style traditionnel. Le buffet se compose d'un plateau avec un motif chêne chaleureux, de tablettes, d'un tiroir et d'un espace de rangement dissimulé derrière des portes rainurées.",'Buffet, blanc, 120x38x90 cm','Buffets et consoles', true,['Panneau de particules','Panneau de fibres de bois']], //Panneau de particules, fibres de bois
            ['HEMNES', 249,['hemnes-console.avif','hemnes-console2.avif','hemnes-console3.jpg'],"Un meuble à la beauté naturelle. Fabriqué en pin massif, un matériau résistant qui garde son aspect authentique au fil des ans. Il se combine parfaitement avec d'autres meubles de la série HEMNES.",'Desserte, teinté blanc, 157x40 cm','Buffets et consoles', true,['Pin massif']], // Pin massif
            ['IDANÄS', 199, ['idanaes-console.avif', 'idanaes-console2.avif','idanaes-console3.jpg'],"La série IDANÄS allie design intemporel et fonctionnalité moderne. Cette desserte en bois ajoute une note d'élégance à n'importe quelle pièce. Esthétique et pratique, elle est d'une grande utilité dans toute la maison.",'Desserte, blanc, 104x32x95 cm','Buffets et consoles', true,['Panneau de particules','Panneau de fibres de bois']], //Panneau de particules, fibres de bois
            ['HOLMERUD', 49.99,['holmerud-table.avif','holmerud-table2.avif','holmerud-table3.jpg'],"La table d'appoint HOLMERUD a une forme architecturale distincte et dispose de nombreux espaces de rangement. Elle a été conçue pour se placer le long de l'accoudoir du canapé, mais elle sera tout aussi élégante installée contre un mur.","Table d'appoint , motif chêne, 80x31 cm",'Buffets et consoles', true,['Panneau de particules','Papier métallisé','Plastique']], //Panneau de particules, papier métallisé, Bord en plastique
            ['HAVSTA', 149,['havsta-desserte.avif','havsta-desserte2.avif','havsta-desserte3.jpg'],"Des détails bien pensés en pin massif avec une surface brossée donnent un charme rustique à votre intérieur. Cette déserte ajoute de l'espace de rangement et permet aussi d'exposer des objets ou des éléments de décoration.",'Desserte, gris-beige, 100x35x63 cm','Buffets et consoles', true,['Pin massif','Vernis acrylique incolore']], //Pin massif, vernis acrylique incolore
            ['FJÄLLBO', 199,['fjaellbo-buffet.avif','fjaellbo-buffet2.avif','fjaellbo-buffet3.jpg'],"Un buffet léger mais stable de style rustique, qui convient aussi bien dans un salon que dans une salle à manger. Le métal lui donne un côté industriel et le bois massif rend chaque produit unique.",'Buffet, noir','Buffets et consoles', true,['Pin massif','Vernis acrylique incolore','Acier']], //Pin massif, acier, vernis acrylique incolore
            ['SPIKSMED', 79.99,['spiksmed-buffet.avif','spiksmed-buffet2.avif','spiksmed-buffet3.jpg'],"Une conception simple mais très fonctionnelle car elle offre de nombreuses possibilités pour s'intégrer à votre intérieur. Ce buffet se compose de rangements ouverts et fermés avec des portes coulissantes et deux tiroirs pratiques sur le dessus.",'Buffet, gris clair, 97x40x79 cm','Buffets et consoles', false,['Panneau de particules','Papier métallisé','Plastique']], // Panneau de particules, peinture acrylique, papier métallisé, Bord en plastique
            // 8 meubles TV + 1 épuisé
            ['KALLAX TV', 59.99, ['kallax.avif', 'kallax2.avif','kallax3.avif'],"Un banc TV assez grand pour accueillir votre télévision et les accessoires multimédia qui vont avec. Pour obtenir des rangements fermés, installez des boîtes dans les compartiments du bas. La partie ouverte permet de faire passer les câbles.",'Banc TV, blanc, 147x60 cm','Meubles TV', true,['Panneau de fibres de bois',"Panneau de particules"]], //Panneau de particules, Panneau de fibres de bois
            ['BESTÅ TV', 1045, ['besta-rangement-tv.avif','besta-rangement-tv2.jpg','besta-rangement-tv3.jpg'],"Les combinaisons de rangement BESTÅ accueillent votre télévision, ainsi que tous les gadgets et accessoires que vous utilisez pour regarder un film, jouer à des jeux vidéos, etc. Cachez le désordre et exposez vos objets préférés dans un même rangement.",'Rangement TV/vitrines, blanc Sindvik/Lappviken gris clair/beige, 300x42x231 cm','Meubles TV', true,['Panneau de fibres de bois',"Panneau de particules"]], //Panneau de particules et panneau de fibres de bois
            ['STOCKHOLM', 399,['stockholm-banc-tv.avif','stockholm-banc-tv2.avif','stockholm-banc-tv3.avif'],"Un banc TV en placage de noyer tout en simplicité. Un classique rétro pour des besoins modernes. Panneaux ouverts ou fermés, c'est vous qui choisissez. Lorsqu'ils sont relevés, ils disparaissent sous le plateau supérieur.",'Banc TV, plaqué noyer, 160x40x50 cm','Meubles TV', true,['Placage noyer']], //Placage noyer
            ['BESTÅ BURS', 299,['besta-burs-banc-tv.avif','besta-burs-banc-tv.avif','besta-burs-banc-tv3.jpg'],"Le banc TV BESTÅ est parfait pour accueillir votre télévision et tous les équipements multimédia qui vont avec. Les tiroirs spacieux offrent de nombreux espaces de rangement pour les DVD et jeux vidéo. Et l'espace est parfaitement rangé !",'Banc TV, brillant blanc, 180x41x49 cm','Meubles TV', true,['Panneau de particules','Plastique']], // Panneau de particules, plastique
            ['FJÄLLBO TV', 149, ['fjaellbo-banc-tv-noir.avif','fjaellbo-banc-tv-noir1.avif','fjaellbo-banc-tv-noir2.avif'],"Comme les portes laissent passer les signaux de votre télécommande, vos appareils électroniques s'entendront bien avec FJÄLLBO. Vous aussi, probablement - l'arrière ouvert permet de gérer facilement les câbles.", 'Banc TV, noir, 150x36x54 cm', 'Meubles TV', true,['Acier','Chêne massif']], //Acier et chêne massif
            ['SKRUVBY TV', 179.94,['skruvby-meuble-tv.avif','skruvby-meuble-tv2.avif','skruvby-meuble-tv3.avif'],"La série SKRUVBY offre un aspect traditionnel avec des rangements indépendants qui peuvent être coordonnées. Ce meuble-TV avec rangements ouverts ajoute une touche chaleureuse à la pièce ; vous pouvez y exposer tout ce que vous aimez.",'Combinaison meuble TV, blanc, 216x38x140 cm','Meubles TV', true,['Panneau de fibres de bois',"Panneau de particules",'Papier métallisé']], // Panneau de fibres de bois, Panneau de particules, papier metalisé
            ['Cunntadh', 620,['cunntadh-rangement-tv.avif','cunntadh-rangement-tv2.webp','cunntadh-rangement-tv3.webp'],"Les combinaisons de rangement accueillent votre télévision, ainsi que tous les gadgets et accessoires que vous utilisez pour regarder un film, jouer à des jeux vidéos, etc. Cachez le désordre et exposez vos objets préférés dans un même rangement.",'Rangement TV/vitrines, blanc Sindvik/Studsviken blanc, 240x42x190 cm','Meubles TV', true,['Panneau de fibres de bois',"Panneau de particules",'Papier métallisé']], //Panneau de particules et panneau de fibres de bois, papier metalisé
            ['VITTSJÖ', 49.99,['vittsjoe-banc-tv.avif','vittsjoe-banc-tv2.avif','vittsjoe-banc-tv3.avif'],'Un banc TV au design simple qui peut accueillir votre télévision et les équipements multimédia qui vont avec. La tablette du milieu, en verre trempé, lui donne une impression de légèreté','Banc TV, brun noir/verre, 100x36x53 cm','Meubles TV', true,['Acier','Verre trempé']], //Acier, verre trempé
            ['BRIMNES',99.95,['brimnes-banc-tv.avif','brimnes-banc-tv2.avif','brimnes-banc-tv3.avif'], "Quand le coin télé est bien rangé, il est plus facile d'organiser une soirée série ! Vous pouvez ranger vos jeux, télécommandes et accessoires dans les grands tiroirs et passer les câbles dans les trous situés à l'arrière.",'Banc TV, blanc, 120x41x53 cm','Meubles TV', false,['Panneau de particules','Papier métallisé']], // Panneau de particules, papier métallisé
            //8 Canapés + 1 epuisé
            ['FRIHETEN', 549,['friheten-canape.avif','friheten-canape2.avif','friheten-canape3.avif'],"Après une bonne nuit de sommeil, votre chambre redevient facilement un salon. Le rangement sous l'assise est facile d'accès et large ce qui permet de ranger de nombreux draps, coussins ou livres.",'Canapé convertible angle+rangement, Skiftebo gris foncé','Canapés', true,['Panneau de particules','Panneau de fibres de bois','Tissu 100% polyester']], // Panneau de particules, Panneau de fibres de bois, Tissu 100 % polyester
            ['VIMLE', 649, ['vimle-canape.avif','vimle-canape2.avif','vimle-canape3.avif'],"Les canapés VIMLE sont composés de modules qui peuvent être combinés comme vous le souhaitez pour créer un canapé sur mesure. Il répond ainsi à vos besoins, s'adapte à votre espace et peut même s'agrandir en même temps que la famille.",'Canapé 3 places, Gunnared beige',"Canapés", true,['Panneau de particules','Panneau de fibres de bois','Tissu 100% polyester']], //Panneau de fibres de bois, Panneau de particules, bois, Tissu 100 polyster
            ['SÖDERHAMN', 949,['soederhamn-canape.avif','soederhamn-canape2.avif','soederhamn-canape3.avif'],"Si vous aimez le style aérien, essayez ces sièges profonds. Asseyez-vous et détendez-vous, seul ou entouré de toute la famille.",'Canapé 4 places, avec méridienne et sans accoudoir/Viarp beige/brun','Canapés', true,['Panneau de fibres de bois','Tissu 100% polyester','Acier']],//Panneau de fibres de bois, acier, bois, tissu 100 polyster
            ['EKTORP', 499,['ektorp-canape.avif','ektorp-canape2.avif','ektorp-canape3.jpg'],"Intemporels, nos chers canapés EKTORP disposent de merveilleux coussins épais et confortables. Les housses sont faciles à changer, alors achetez-en une ou deux de plus pour pouvoir alterner en fonction de vos envies ou de la saison.",'Canapé 3 places, Hakebo gris foncé','Canapés', true,['Panneau de particules','Panneau de fibres de bois','Tissu 100% polyester']], // Panneau de fibres de bois, Panneau de particules, bois, Tissu 100 polyster 
            ['VIMLE2', 1099, ['vimle-canape-4-places.avif','vimle-canape-4-places2.avif','vimle-canape-4-places3.avif'],"Les canapés VIMLE sont composés de modules qui peuvent être combinés comme vous le souhaitez pour créer un canapé sur mesure. Il répond ainsi à vos besoins, s'adapte à votre espace et peut même s'agrandir en même temps que la famille.",'Canapé 4 places + méridienne, Gunnared beige','Canapés', true,['Panneau de particules','Panneau de fibres de bois','Tissu 100% polyester']], // Panneau de fibres de bois, Panneau de particules, bois, Tissu 100 polyster
            ['BÅRSLÖV', 699,['barsloev-convertible.avif','barsloev-convertible2.avif','barsloev-convertible3.avif'],"Les formes arrondies et les grands coussins moelleux vous donneront envie de passer du temps dans le canapé BÅRSLÖV. Vous pouvez vous y asseoir ou dormir et utiliser les rangements pratiques sous l'assise. Le tissu est facile à entretenir.",'Convertible 3 places + méridienne, Tibbleby beige/gris','Canapés', true,['Panneau de particules','Tissu 100% polyester','Lamibois']], // Lamibois, Panneau de particules, bois, Tissu 100 polyster
            ['ANGERSBY', 299,['angersby-canape.avif','angersby-canape2.avif','angersby-canape3.avif'],"Un canapé 3 places confortable, à petit prix, avec des poches latérales pour ranger télécommandes, lunettes et autres petits accessoires à avoir à portée de main. Comment résister ?",'Canapé 3 places, Knisa gris clair','Canapés',true,['Lamibois','Acier','Tissu 100% polyester']], // Acier, Lamibois, Tissu 100 Polyster
            ['KIVIK', 599,['kivik-canape.avif','kivik-canape2.avif','kivik-canape3.avif'],"Installez-vous bien confortablement dans le canapé KIVIK . Ses dimensions, les accoudoirs bas, les ressorts ensachés avec la mousse qui s’adaptant aux contours du corps invitent à la sieste, à recevoir vos amis ou à vous y asseoir pour vous détendre.",'Canapé 3 places, Tibbleby beige/gris','Canapés', true,['Panneau de particules','Panneau de fibres de bois','Tissu 100% polyester']], // Panneau de fibres de bois, Panneau de particules, Tissu 100 polyster
            ['JÄTTEBO',1295,['jaettebo-canape.avif','jaettebo-canape2.avif','jaettebo-canape3.avif'],"Le canapé modulable JÄTTEBO est idéal pour passer une soirée entre amis ou en famille. Combinaison avec une confortable méridienne ou canapé personnalisé dans le style qui vous convient.",'Canapé 2,5 places avec méridienne, gauche avec appuie-tête/Samsala gris-beige','Canapés', false,['Lamibois','Panneau de particules','Panneau de fibres de bois','Tissu 100% polyester']], // Lamibois, Panneau de particules, Panneau de fibres de bois, Tissu 100 polyster
            //Selon CdC avoir au moins 8 meubles par catégorie car on doit ajouter 6 produits simulaires en suggestion (même catégorie) et avoir au moins 1 épuisé de chaque
        ];  
    }
    
    private function loadOrderProduct(ObjectManager $manager): void
    {
        foreach ($this->getOrderProductData() as [$email, $productName, $quantity, $orderReference]) {
            $orderProduct = new OrderProduct();
            $user = $this->getReference($email);
            $orderProduct->setIdUser($user);
            $product = $this->getReference($productName);
            $orderProduct->setIdProduct($product);
            $orderProduct->setQuantity($quantity);
            $manager->persist($orderProduct);
            $this->addReference($orderReference, $orderProduct);
        }
    }

    private function getOrderProductData(): array
    {
        return [
            ['john@symfony.com','SÖDERHAMN',1,"john1"],
            ["john@symfony.com",'BESTÅ TV',1,"john2"],
            ["john@symfony.com",'MALM',1,"john3"],
            ["jane@symfony.com",'ODGER',6,"jane1"],
            ["jane@symfony.com",'PINNTORP',1,"jane2"],
            ["cyril@symfony.com",'KALLAX',1,"cyril1"]
        ];
    }

    private function loadOrders(ObjectManager $manager): void
    {
        foreach ($this->getOrderData() as [$email, $state, $deliveryAddress, $dateOrder, $orderProducts]) {
            $order = new Order();
            $user = $this->getReference($email);
            $order->setIdUser($user);
            $order->setState($state);
            $adress = $this->getReference($deliveryAddress);
            $order->setIdAdress($adress);
            $date = new \DateTime($dateOrder);
            $order->setDate($date);
            foreach ($orderProducts as $orderProduct) {
                $order->addOrderProduct($this->getReference($orderProduct));
            }
            $manager->persist($order);
        }
    }

    private function getOrderData(): array
    {
        return [
            // en cours de livraison
            ['john@symfony.com','en cours de livraison','JohnAdress','2024-05-10',['john1','john2','john3']],
            // commandé
            ['jane@symfony.com','commandé','JaneAdress','2024-05-10',['jane1','jane2']],
            // livré
            ['cyril@symfony.com','livré','CyrilAdress','2024-04-23',['cyril1']]
        ];
    }

}
