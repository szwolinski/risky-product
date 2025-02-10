## Data: 2025-01-09

## Status
Postanowione

## Kontekst
Należy wybrać mechanizm, który odpowiedzialny będzie za walidację, czy został dodany do koszyka więcej niż 1 niebezpieczny produkt

## Rozwiązania
#### Opcja 1: Użyć cart validatora
#### Opcja 2: Wykorzystać eventy lub decorator

## Decyzja
Po przeanalizowaniu problemu zarówno ze strony technicznej jak i biznesowej, padła decyzja na wykorzystanie cart validatora.

## Konsekwencje

- Będzie istniała możliwość dodania do koszyka więcej niż 1 niebezpieczny produkt. Blokada będzie dopiero w momencie próby złożenia zamówienia.
- Klienci będą w stanie odwlec decyzje o tym, który produkt ostatecznie będą chcieli zakupić. Unikną oni frustującej sytuacji, w której byliby zmuszeni do usunięcia jednego z produktów, żeby dodać drugi.