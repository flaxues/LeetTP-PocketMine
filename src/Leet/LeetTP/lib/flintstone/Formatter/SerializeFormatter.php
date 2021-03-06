<?php

/*
 * This file is part of the Flintstone package.
 *
 * (c) Jason M <emailfire@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Leet\LeetTP\lib\flintstone\Formatter;

class SerializeFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function encode($data)
    {
        return serialize($this->preserveLines($data, false));
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data)
    {
        return $this->preserveLines(unserialize($data), true);
    }

    /**
     * Preserve new lines, recursive function.
     *
     * @param mixed $data
     * @param bool $reverse
     *
     * @return mixed
     */
    protected function preserveLines($data, $reverse)
    {
        $search = array("\n", "\r");
        $replace = array('\\n', '\\r');
        if ($reverse) {
            $search = array('\\n', '\\r');
            $replace = array("\n", "\r");
        }

        if (is_string($data)) {
            $data = str_replace($search, $replace, $data);
        } elseif (is_array($data)) {
            foreach ($data as &$value) {
                $value = $this->preserveLines($value, $reverse);
            }
            unset($value);
        }

        return $data;
    }
}
