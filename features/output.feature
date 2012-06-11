Feature: Output

    In order to specify expected output
    As a developer using Behat
    I want to use the "the output should contain" step

        Scenario: Run unknown command
            When I run `neverever gonna work`
            Then the output should contain:
                """
                neverever: not found
                """

        Scenario: Detect subset of one-line output
            When I run `php -r 'echo "hello world";'`
            Then the output should contain "hello world"

        Scenario: Detect subset of one-line output
            When I run `echo 'hello world'`
            Then the output should contain "hello world"

        Scenario: Detect absence of one-line output
            When I run `php -r 'echo "hello world";'`
            Then the output should not contain "good-bye"

        Scenario: Detect subset of multiline output
            When I run `php -r 'echo "hello world";'`
            Then the output should contain:
                """
                hello
                """

        Scenario: Detect subset of multiline output
            When I run `php -r 'echo "hello\\nworld";'`
            Then the output should not contain:
                """
                good-bye
                """
