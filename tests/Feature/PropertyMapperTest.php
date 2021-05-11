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

    public function test_it_generates_correct_inbound_properties()
    {
        $expected = [
            'property_1' => 'Prop 1 Value',
            'property_2' => 'Prop 2 Value',
            'property_4' => 'Prop 4 Value'
        ];

        $model = new ExamplePropertyModel($expected);

        $provider = $this->fakeProvider();

        $mapper = new PropertyMapper($model, PropertyMapper::ENTITY_CONTACT, $provider);

        $this->assertEquals(
            $expected,
            $mapper->mapInbound([
                'prop1' => 'Prop 1 Value',
                'prop2' => 'Prop 2 Value',
                'prop3' => 'Prop 3 Value (Should not be mapped)',
                'prop4' => 'Prop 4 Value'
            ])
        );
    }

    public function test_it_sets_correct_inbound_properties()
    {
        $expected = [
            'property_1' => 'New Prop 1',
            'property_2' => 'New Prop 2',
            'property_3' => 'Property 3 Value',
            'property_4' => 'New Prop 4'
        ];

        $model = new ExamplePropertyModel($expected);

        $provider = $this->fakeProvider();

        $mapper = new PropertyMapper($model, PropertyMapper::ENTITY_CONTACT, $provider);
        
        $mapper->setInbound([
            'prop1' => 'New Prop 1',
            'prop2' => '_New Prop 2',
            'prop3' => 'Prop 3 Value (Should not be mapped)',
            'prop4' => '_New Prop 4'
        ]);

        $this->assertEquals(
            $expected,
            $model->getAttributes()
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
            'property_4' => 'Should Convert to Null',
        ]);
    }

    public function getProperty2AttributeForFakeProviderRelay($originalValue = null)
    {
        return $originalValue[0];
    }

    public function getProperty2AttributeForRelay($originalValue = null)
    {
        return 'Wrong Priority';
    }

    public function getProperty4AttributeForRelay($originalValue = null)
    {
        return null;
    }

    public function setProperty2AttributeForFakeProviderRelay($value = null)
    {
        $this->attributes['property_2'] = substr($value, 1);
    }

    public function setProperty2AttributeForRelay($value = null)
    {
        $this->attributes['property_2'] = 'Wrong Priority';
    }

    public function setProperty4AttributeForRelay($value = null)
    {
        $this->attributes['property_4'] = substr($value, 1);
    }
}
