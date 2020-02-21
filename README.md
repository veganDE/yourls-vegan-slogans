# Vegan slogans

## https://sho.rt/thEre-Is-nO-EXCuSe-fOR-AnimAL-aBusE

  Showcase your moral superiority by turning every link you send to people into a vegan message.

## How does it work?
  - This plugin replaces the URL generation method with a custom one using vegan slogans.
  - By default there are 14 possible slogans:
```
animals feel pain
meat is murder
dairy is murder
you are not a baby cow bro
animals are individuals not ingredients
animals are friends not food
milk is for babies
there is no excuse for animal abuse
animal agriculture is killing our planet
your choice has a victim
how can you love animals and eat them
animal lovers dont eat animals
choose fries not lives
end speciesism
```
  - You can edit the list of possible slogans on the plugin administration page.
    - (Credit to peterberbecs' [Keywords, Charset & Length](https://github.com/peterberbec/yourls-keyword_charset_length) plugin for the base of the settings page.)
  - Uniqueness of the URLs comes from the mixing of lower- and uppercase letters.
    - So there are 2^n possible combinations for an n-letter-slogan, and even more when enabling numerical substitution (o => 0, i => 1, s => 5, z => 2).

## How to install
  - In /user/plugins, create a new folder named yourls-vegan-slogans
  - Drop these files (the plugin.php) in that directory
  - Go to the Plugins administration page (e.g. https://sho.rt/admin/plugins.php) and activate the plugin
  - Go to the new administration page (e.g. https://sho.rt/admin/plugins.php?page=vegan_slogans) to configure
  - Have fun!
