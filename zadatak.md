# prvi zadatak x
- Koristiti maker-bundle za kreiranje entiteta Company i User, postoji relacija između njih (OneToMany). 
- HINT: https://symfony.com/bundles/SymfonyMakerBundle/current/index.html
- Polja koja su potrebna u oba entiteta definišite sami po potrebi, kasnije ćete ih širiti svakako.
- Napraviti tipove (role) User-a, gde jedan podtip User-a odgovara zaposlenom u kompaniji (Company).
- Koristiti enum tip za definisanje tipova User-a.
- HINT: https://symfony.com/doc/current/reference/forms/types/enum.html
- Omogućiti uvezivanje User-a odgovarajućeg tipa sa kompanijom kojoj pripada.

# drugi zadatak x
- Napisati komandu koja će dodati 10 kompanija u bazu podataka.
- Napisati komandu koja će dodati 100 User-a u bazu podataka, svaki User pripada jednoj od 10 kompanija.
- Napisati komandu koja radi cleanup svih podataka.
- HINT: https://symfony.com/doc/current/console.html

# treći zadatak x
- Napraviti REST API za kompanije i User-e. Odnosno omogućiti CRUD operacije nad kompanijama i User-ima.
- Omogućiti filtriranje User-a po kompaniji.
- Koristiti Symfony routing za definisanje ruta.
- Koristity FormType za validaciju podataka.
- HINT: https://symfony.com/doc/current/routing.html
- HINT: https://symfony.com/doc/current/forms.html

# četvrti zadatak
- Napisati testove za sve akcije koje ste napravili u trećem zadatku.
- HINT: https://symfony.com/doc/current/testing.html

# peti zadatak x
- Koristiti Symfony serializer, za serilizaciju i deserilizaciju podataka.
- Dodati context-ne grupe na kontrolere koje ste napravili u trećem zadatku.
- HINT: https://symfony.com/doc/current/serializer.html

# šesti zadatak x
- Napraviti document Ad (MongoDB), sa poljima koji zvuče logično za oglas za prodaju/izdavanje nekretnine.
- Ad (oglas) na sebi ima userId, upućuje na id User-a koji je postavio oglas.
- Ad (oglas) ima i companyId, upućuje na id kompanije koja je postavila oglas, ali i ne mora da ga ima, pošto User ne mora pripadati kompaniji.
- HINT: https://www.mongodb.com/ - https://symfony.com/doc/current/bundles/DoctrineMongoDBBundle/index.html

# sedmi zadatak x
- Napraviti REST API za oglas (Ad) koji ste napravili u petom zadatku.
- Omogućiti CRUD operacije nad oglasima.
- Omogućiti filtriranje oglasa po User-u i po kompaniji.

# osmi zadatak
- Napisati testove za sve akcije koje ste napravili u šestom zadatku.

# deveti zadatak x
- Napisati sh skriptu koja će obrisati symfony cache i pokrenuti sve testove.

# deseti zadatak x
- Napisati komandu koja će dodati 1000 Ad-ova u bazu podataka sa random podacima ali polje kada je Ad postavljen (datum postavke) treba da budu u razmaku prethodna tri meseca.
- Napisati komandu koja će izvući oglase kojima je datum postavke stariji od 30 dana ali mlađi od 60 dana i generisati csv sa par podataka iz oglasa.
- Koristiti AdRepository za rad sa bazom podataka.
- HINT: https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository
- Napisati cleanup komandu.

# BONUS zadatak x
- Istražiti autentifikaciju u Symfony-u.
- Pokušati implementirati autentifikaciju za User-a.
- HINT: https://symfony.com/doc/current/security.html