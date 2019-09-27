<?php
namespace FtwSoft\NotificationChannels\Intercom\Tests\Mocks;

use Illuminate\Contracts\Config\Repository;

class TestConfigRepository implements Repository
{
    /**
     * @var array
     */
    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value = null)
    {
        $this->data[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function prepend($key, $value)
    {
        $this->data = array_reverse($this->data);
        $this->data[$key] = $value;
        $this->data = array_reverse($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function push($key, $value)
    {
        unset($this->data[$key]);
        $this->data[$key] = $value;
    }
}
