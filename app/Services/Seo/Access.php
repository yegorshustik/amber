<?php

namespace App\Services\Seo;

use Illuminate\Support\Str;

trait Access
{
    protected array $data = [];

    protected array $extensions = [];

    public function __set(string $key, $value): void
    {
        $this->set($key, $value);
    }

    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    public function set(string $key, $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function get(string $key): mixed
    {
        if (isset($this->extensions[Str::camel($key)])) {
            return $this->extensions[Str::camel($key)];
        }

        $data = $this->data[$key] ?? null;

        if (is_object($data) && method_exists($data, '__toString')) {
            return $data->__toString() ?? $this->defaults[$key] ?? null;
        }

        return select_multilingual_field_value($data, $this->defaults[$key] ?? null);
    }

    public function getRaw(string $key): mixed
    {
        return $this->data[$key] ?? $this->defaults[$key] ?? null;
    }

    public function extend(PluginInterface|HeadPluginInterface $plugin): self
    {
        $this->extensions[Str::camel($plugin->slug())] = $plugin;

        return $this;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->data[$offset] ?? null;
    }
}
