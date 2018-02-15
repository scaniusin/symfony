#Feature: Handle user registration via the RESTful API
#
#  In order to allow a user to sign up
#  As a client software developer
#  I need to be able to handle registration
#
#  Background:
#    Given there are Users with the following details:
#      | id | username | email          | password |
#      | 1  | peter    | peter@test.com | testpass |
#    And I set header "Content-Type" with value "application/json"
#
#  @this
#  Scenario: Can register with valid data
#    When I send a "POST" request to "/register" with body:
#      """
#      {
#        "email": "chris@codereviewvideos.com",
#        "username": "chris",
#        "plainPassword": {
#          "first": "abc123",
#          "second": "abc123"
#        }
#      }
#      """
#    Then the response code should be 201
#    And the response should contain "The user has been created successfully"
#    When I am successfully logged in with username: "chris", and password: "abc123"
#    And I follow the link in the Location response header
#    And I send a "GET" request to "/profile/2"
#    And the response should contain json:
#      """
#      {
#        "id": "2",
#        "username": "chris",
#        "email": "chris@codereviewvideos.com"
#      }
#      """
#
#  Scenario: Cannot register with existing user name
#    When I send a "POST" request to "/register" with body:
#      """
#      {
#        "email": "peter@some-different-domain.com",
#        "username": "peter",
#        "plainPassword": {
#          "first": "abc123",
#          "second": "abc123"
#        }
#      }
#      """
#    Then the response code should be 400
#    And the response should contain "The username is already used"
#
#  Scenario: Cannot register with an existing email address
#    When I send a "POST" request to "/register" with body:
#      """
#      {
#        "email": "peter@test.com",
#        "username": "different_peter",
#        "plainPassword": {
#          "first": "abc123",
#          "second": "abc123"
#        }
#      }
#      """
#    Then the response code should be 400
#    And the response should contain "The email is already used"
#
#  Scenario: Cannot register with an mismatched password
#    When I send a "POST" request to "/register" with body:
#      """
#      {
#        "email": "gary@test.co.uk",
#        "username": "garold",
#        "plainPassword": {
#          "first": "gaz123",
#          "second": "gaz456"
#        }
#      }
#      """
#    Then the response code should be 400
#    And the response should contain "The entered passwords don't match"


Feature: Handle user registration via the RESTful API

  In order to allow a user to sign up
  As a client software developer
  I need to be able to handle registration


  Background:
    Given there are Users with the following details:
      | id | username | email          | password |
      | 1  | peter    | peter@test.com | testpass |
    And I set header "Content-Type" with value "application/json"


  Scenario: Can register with valid data
    When I send a "POST" request to "/register" with body:
      """
      {
        "email": "gary@test.co.uk",
        "username": "garold",
        "plainPassword": {
          "first": "gaz123",
          "second": "gaz123"
        }
      }
      """
    Then the response code should be 201
    And the response should contain "The user has been created successfully"
    When I am successfully logged in with username: "garold", and password: "gaz123"
    And I send a "GET" request to "/profile/2"
    And the response should contain json:
      """
      {
        "id": "2",
        "username": "garold",
        "email": "gary@test.co.uk"
      }
      """

  Scenario: Cannot register with existing user name
    When I send a "POST" request to "/register" with body:
      """
      {
        "email": "gary@test.co.uk",
        "username": "peter",
        "plainPassword": {
          "first": "gaz123",
          "second": "gaz123"
        }
      }
      """
    Then the response code should be 400
    And the response should contain "The username is already used"

  Scenario: Cannot register with an existing email address
    When I send a "POST" request to "/register" with body:
      """
      {
        "email": "peter@test.com",
        "username": "garold",
        "plainPassword": {
          "first": "gaz123",
          "second": "gaz123"
        }
      }
      """
    Then the response code should be 400
    And the response should contain "The email is already used"

  Scenario: Cannot register with an mismatched password
    When I send a "POST" request to "/register" with body:
      """
      {
        "email": "gary@test.co.uk",
        "username": "garold",
        "plainPassword": {
          "first": "gaz123",
          "second": "gaz456"
        }
      }
      """
    Then the response code should be 400
    And the response should contain "The entered passwords don't match"