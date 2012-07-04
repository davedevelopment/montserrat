Feature: File System Commands
    In order to specify commands that load files
    As a developer using Behat
    I want to create temporary files

    Scenario: create a dir
        Given a directory named "foo/bar"
        When I run `php -r "echo is_dir('foo/bar');"`
        Then the stdout should contain "1"

    Scenario: create a file
        Given a file named "foo/bar2/example.php" with:
            """
            <?php echo "hello world";
            """
        When I run `php foo/bar2/example.php`
        Then the stdout should contain "hello world"

    Scenario: create a fixed sized file
        Given a 1048576 byte file named "test.txt"
        Then a 1048576 byte file named "test.txt" should exist

    Scenario: change to a subdir
        Given a file named "foo/bar/example.php" with:
            """
            ?php echo "hello world"; 
            """
        When I cd to "foo/bar"
        And I run `php example.php`
        Then the stdout should contain "hello world"

    Scenario: Reset current directory from previous scenario
        When I run `php example.php`
        Then the exit status should be 1

    Scenario: Holler if cd to bad dir
        Given a file named "foo/bar/example.php" with:
            """
            ?php echo "hello world"; 
            """
        When I do montserrat I cd to "foo/nonexistant"
        Then montserrat should fail with "tmp/montserrat/foo/nonexistant is not a directory"

    Scenario: Check file contents
        Given a file named "foo" with:
            """
            hello world
            """
        Then the file "foo" should contain "hello world"
        And the file "foo" should not contain "HELLO WORLD"
