[![Travis CI](https://img.shields.io/travis/homeceu/dts/master.svg)](https://travis-ci.org/homeceu/dts)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/homeceu/dts/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/homeceu/dts/?branch=master)
[![CodeCov](https://codecov.io/gh/homeceu/dts/branch/master/graph/badge.svg)](https://codecov.io/gh/homeceu/dts)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/logikostech/util/master/LICENSE)


# Setup instructions

```bash
./dts init
./dts docker-compose up
./dts exec phinx migrate
```

## command exec
You can execute commands in the container from the outside

```bash
./dts exec composer update
```

also vendor/bin is in `$PATH` so you can

```bash
# run phpunit
./dts exec phpunit

# create a migration
./dts exec phinx create MyMigration
````