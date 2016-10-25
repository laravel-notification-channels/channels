<?php

namespace NotificationChannels\DiscordWebhook\Test;

use Illuminate\Support\Arr;
use NotificationChannels\DiscordWebhook\DiscordWebhookEmbed;

class DiscordWebhookEmbedTest extends \PHPUnit_Framework_TestCase
{
    /** @var \NotificationChannels\DiscordWebhook\DiscordWebhookEmbed */
    protected $embed;

    public function setUp()
    {
        parent::setUp();
        $this->embed = new DiscordWebhookEmbed();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /** @test */
    public function it_can_set_the_title()
    {
        $this->embed->title('my title');
        $this->assertEquals('my title', $this->embed->title);
    }

    /** @test */
    public function it_can_set_the_title_and_url()
    {
        $this->embed->title('with url', 'title_url');
        $this->assertEquals('with url', $this->embed->title);
        $this->assertEquals('title_url', $this->embed->url);
    }

    /** @test */
    public function it_can_set_the_description()
    {
        $this->embed->description('my description');
        $this->assertEquals('my description', $this->embed->description);
    }

    /** @test */
    public function it_can_set_the_color()
    {
        $this->embed->color(123);
        $this->assertEquals(123, $this->embed->color);
    }

    /** @test */
    public function it_can_set_the_footer_text()
    {
        $this->embed->footer('my footer');
        $this->assertEquals('my footer', Arr::get($this->embed->footer, 'text'));
    }

    /** @test */
    public function it_can_set_the_footer_text_and_icon_url()
    {
        $this->embed->footer('with icon', 'footer_icon');
        $this->assertEquals('with icon', Arr::get($this->embed->footer, 'text'));
        $this->assertEquals('footer_icon', Arr::get($this->embed->footer, 'icon_url'));
    }

    /** @test */
    public function it_can_set_the_image_url()
    {
        $this->embed->image('image_url');
        $this->assertEquals('image_url', Arr::get($this->embed->image, 'url'));
    }

    /** @test */
    public function it_can_set_the_thumbnail_url()
    {
        $this->embed->thumbnail('thumb_url');
        $this->assertEquals('thumb_url', Arr::get($this->embed->thumbnail, 'url'));
    }

    /** @test */
    public function it_can_set_the_author_name()
    {
        $this->embed->author('me');
        $this->assertEquals('me', Arr::get($this->embed->author, 'name'));
    }

    /** @test */
    public function it_can_set_the_author_name_and_url()
    {
        $this->embed->author('with url', 'author_url');
        $this->assertEquals('with url', Arr::get($this->embed->author, 'name'));
        $this->assertEquals('author_url', Arr::get($this->embed->author, 'url'));
    }

    /** @test */
    public function it_can_set_the_author_name_and_url_and_icon()
    {
        $this->embed->author('with icon', 'author_and_icon_url', 'for author');
        $this->assertEquals('with icon', Arr::get($this->embed->author, 'name'));
        $this->assertEquals('author_and_icon_url', Arr::get($this->embed->author, 'url'));
        $this->assertEquals('for author', Arr::get($this->embed->author, 'icon_url'));
    }

    /** @test */
    public function it_can_embed_field()
    {
        $this->embed->field('my name', 'my value');
        $this->assertEquals('my name', Arr::get(Arr::first($this->embed->fields)->toArray(), 'name'));
        $this->assertEquals('my value', Arr::get(Arr::first($this->embed->fields)->toArray(), 'value'));
        $this->assertFalse(Arr::get(Arr::first($this->embed->fields)->toArray(), 'inline'));
    }

    /** @test */
    public function it_can_embed_field_inline()
    {
        $this->embed->field('my name', 'my value', true);
        $this->assertEquals('my name', Arr::get(Arr::first($this->embed->fields)->toArray(), 'name'));
        $this->assertEquals('my value', Arr::get(Arr::first($this->embed->fields)->toArray(), 'value'));
        $this->assertTrue(Arr::get(Arr::first($this->embed->fields)->toArray(), 'inline'));
    }
}
