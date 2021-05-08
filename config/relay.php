<?php

return [
    /**
     * Define the Eloquent Model class that represents a contact; That is, a single individual
     * usually with their own individual contact details.
     * 
     * If your application does not support individual contacts, you should leave this null.
     * 
     * In most applications, this would be configured to App\Models\User::class
     */
    'contact' => null, // App\Models\User::class

    /**
     * Define the Eloquent Model class that represents an organization; That is, a related
     * group of individuals, often with a business/organization name and general contact
     * details. In some systems, this would be labelled as 'Company' or 'Business'.
     * 
     * If your application does not support organizations, you should leave this null.
     */
    'organization' => null,


    /**
     * Each provider will usually have it's own configuration, such as access tokens,
     * credentials and whether the provider should supports contacts / organizations.
     * 
     * Refer to the provider's README to see what configuration options are available.
     */
    'providers' => [
        /**
         * For example, Provider XYZ would expect to find the API key here:
         */
        // 'provider_xyz' => [
        //     'api_key' => 'abc-123'
        // ],
    ],
];
