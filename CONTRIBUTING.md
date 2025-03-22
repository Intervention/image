# Contributing to Intervention Image

Thank you for your interest in contributing to the project. Any contribution
that improves the project is welcome and will be considered for integration.

Please understand that there is no guarantee that your pull request will be
accepted. To avoid misunderstandings, it is recommended that you first initiate
a discussion about your planned changes on the [issue
board](https://github.com/Intervention/image/issues).

You agree that your contributions will be licensed under the same license as
the main project. Before submitting a pull request familiarize yourself with
the [license](LICENSE) and understand its terms and conditions.

## Requirements

To ensure a smooth collaboration process and maintain the quality of the
project the submitted pull request must meet the following requirements to be
accepted for review. It is a good idea to check these locally **before** you
submit the PR.

### Follow the Branching Model

Please submit only one feature or bug fix per pull request. The best practice
is to split your contributions into thematically meaningful branches and then
submit them individually as pull requests.

### Comply with the Coding Standard

The project follows the [PSR12 coding
standard](https://www.php-fig.org/psr/psr-12/) with some [additional
extensions](phpcs.xml.dist). Adherence to these rules is essential and can be
easily checked with the following command.

```bash
./vendor/bin/phpcs
```

Or by using the project's Docker environment.

```bash
docker compose run --rm --build standards
```

### Write Tests

For new features or changes to the existing code base, it is essential to cover
them with tests. Integrate your tests in the  the project's test environment
and consider both positive and negative scenarios.

Make sure that all tests are passed with the following command.

```bash
./vendor/bin/phpunit
```

Or by using the project's Docker environment.

```bash
docker compose run --rm --build tests
```

### Pay attention to the Analyzer Results

A static analyzer is also used to avoid bugs and ensure quality. In addition to
testing, it must also run without errors.

Check the analyzer by running the following command.

```bash
./vendor/bin/phpstan analyze --memory-limit=512M ./src
```

Or by using the project's Docker environment.

```bash
docker compose run --rm --build analysis
```

**Thank you!**
