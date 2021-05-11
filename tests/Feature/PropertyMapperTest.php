<?php

namespace TheTreehouse\Relay\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\PropertyMapper;
use TheTreehouse\Relay\Tests\TestCase;

class PropertyMapperTest extends TestCase
{
    protected function configureRelay()
    {
        parent::configureRelay();
        
        config([
            'relay.providers.fake_provider.contact_fields' => [
                'property_1' => 'prop1',
                'property_2' => 'prop2',
                'property_4' => 'prop4',
            ]
        ]);
    }

    public function test_it_generates_correct_outbound_properties()
    {
        $expected = [
            'prop1' => 'Property 1 Value',
            'prop2' => 'Property 2 Value',
            'prop4' => null,
        ];

        $model = new ExamplePropertyModel($expected);

        $provider = $this->fakeProvider();

        $mapper = new PropertyMapper($model, PropertyMapper::ENTITY_CONTACT, $provider);

        $this->assertEquals(
            $expected,
            $mapper->mapOutbound()
        );
    }
}

class ExamplePropertyModel extends Model
{
    protected $guarded = [];

    public function __construct()
    {
        parent::__construct([
            'property_1' => 'Property 1 Value',
            'property_2' => [
                'Property 2 Value'
            ],
            'property_3' => 'Property 3 Value',
            'property_4' => null,
        ]);
    }

    public function getProperty2AttributeForFakeProviderRelay($originalValue = null)
    {
        return $originalValue[0];
    }
}
