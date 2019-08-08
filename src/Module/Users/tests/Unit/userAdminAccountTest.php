<?php
    use Lab19\Cart\Testing\TestCase;

    class TestUserAdminAccount extends TestCase
    {
        public function testCannotCreateAdminPrivileges(): void
        {
            /** @var \Illuminate\Foundation\Testing\TestResponse $response */
            $response = $this->graphQL('
                mutation {
                    createAccount(input:{
                        email:"test@test.com",
                        password: "tester",
                        name: "Luke",
                        is_admin: 1
                        }) {
                        token
                        user {
                            name
                            email
                            id
                            is_admin
                        }
                    }
                }
            ');

            $response->assertSee('errors');
        }

        public function testCanLoginAsAdminUser(): void
        {
            /** @var \Illuminate\Foundation\Testing\TestResponse $response */
            $response = $this->graphQL('
                mutation {
                    createSession(input:{
                        email:"admin@test.com",
                        password: "admin",
                        name: "Luke",
                        is_admin: 1
                        }) {
                        token
                        user {
                            name
                            email
                            id
                            is_admin
                        }
                    }
                }
            ');

            $response->assertSee('errors');
        }
    }