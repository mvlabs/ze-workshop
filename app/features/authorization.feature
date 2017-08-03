Feature: Authorization
  In order to access administration functionalities
  As a user
  I need to be an administrator

  Scenario: Administrator users can access administration functionalities
    Given I am an administrator user
    When I try to access administration functionalities
    Then I am granted access

  Scenario: Non administrator users can not access administration functionalities
    Given I am a non administrator user
    When I try to access administration functionalities
    Then I am denied access

  Scenario: Unknown users can not access administration functionalities
    Given I am a unknown user
    When I try to access administration functionalities
    Then I am denied access