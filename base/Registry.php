<?
namespace base;

abstract class Registry {
	abstract protected function set($key, $val);
	abstract protected function get($key);
}