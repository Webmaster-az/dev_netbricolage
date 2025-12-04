# Marketing With Microsoft(Ps_microsoft)
## Building & Installation

## Building

This part covers the steps to get this project ready locally.

In order to run on a PrestaShop instance, dependencies needs to be downloaded and the JS application built.

### PHP

Retrieve dependencies with composer

```
composer install
# or
make docker-build-composer
```

Composer has been configured in authoritative mode, which means it won't look in the filesystem when a class is not found in the current autoloader.
When a class is added or deleted, it is required to rerun the above command.

### VueJS

The following commands need to be run in the `_dev/` folder.

To build the application in production mode:

```
npm install --n
npm run build

# or 

make vuejs
```

To compiles and watch for new changes (development mode):

```
npm install --n

npm run dev
```