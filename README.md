# Negromovich Authentication Bundle

Security config example `config/packages/security.yaml`:

    security:
        # https://symfony.com/doc/current/security.html#where-do-users-come-from-user-providers
        providers:
            app_user_provider:
                entity:
                    class: Negromovich\AuthenticationBundle\Entity\AuthUser
    
        firewalls:
            dev:
                pattern: ^/(_(profiler|wdt)|css|images|js)/
                security: false
            main:
                anonymous: true
                lazy: true
                provider: app_user_provider
                guard:
                    authenticators:
                        - Negromovich\AuthenticationBundle\Security\FirebaseAuthenticator
                logout:
                    path: negromovich_authentication_logout
                    target: negromovich_authentication_login
    
                # activate different ways to authenticate
                # https://symfony.com/doc/current/security.html#firewalls-authentication
    
                # https://symfony.com/doc/current/security/impersonating_user.html
                # switch_user: true
    
        # Easy way to control access for large sections of your site
        # Note: Only the *first* access control that matches will be used
        access_control:
            - { path: '^/auth', roles: IS_AUTHENTICATED_ANONYMOUSLY, requires_channel: https }
            - { path: '^/', roles: ROLE_CONTENT, requires_channel: https }
    
        role_hierarchy:
            ROLE_ADMIN: [ROLE_AUTH_ADMIN, ROLE_CONTENT]

Config example `config/packages/negromovich_authentication.yaml`:

    negromovich_authentication:
        firebase_service_account_path: '%env(resolve:FIREBASE_CREDENTIALS)%'
        firebase_app_config: {
            apiKey: "%apiKey%",
            authDomain: "%projectId%.firebaseapp.com",
            projectId: "%projectId%",
            appId: "%appId%"
        }
        firebase_ui_config: {
            signInOptions: [
                'google.com'
            ]
        }
        success_redirect_route: 'admin'

Routes example `config/routes/negromovich_authentication.yaml`:

    negromovich_authentication:
        resource: '@NegromovichAuthenticationBundle/config/routes.yaml'
