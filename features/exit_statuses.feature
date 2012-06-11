Feature: exit statuses

  In order to specify expected exit statuses
  As a developer using Behat
  I want to use the "the exit status should be" step

  Scenario: exit status of 0
    When I run `php -h`
    Then the exit status should be 0

  Scenario: Not explicitly exiting at all
    When I run `php -r 'echo "";'`
    Then the exit status should be 0
    
  Scenario: non-zero exit status
    When I run `php -r 'exit(56);'`
    Then the exit status should be 56
    And the exit status should not be 0

  Scenario: Try to run something that doesn't exist
    When I run `does_not_exist`
    Then the exit status should be 127
