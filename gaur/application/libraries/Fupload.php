<?php
/**
 * Gaur
 *
 * An open source web application
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017 - 2018, Krishnan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    Gaur
 * @author     Krishnan <krishnan57474@gmail.com>
 * @copyright  Copyright (c) 2017 - 2018, Krishnan
 * @license    https://opensource.org/licenses/MIT   MIT License
 * @link       https://github.com/krishnan57474
 * @since      Version 3.0.0
 */

defined('BASEPATH') OR exit;

/**
 * File uploader
 *
 * @package     Gaur
 * @subpackage  Library
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Fupload
{
    /**
     * Upload error
     *
     * @var string
     */
    private $upload_error;

    /**
     * Get values from $_FILES array
     *
     * @param   string  array key
     *
     * @return  array
     */
    public function files($key)
    {
        $files = array();

        if (!key_exists($key, $_FILES))
        {
            return $files;
        }

        foreach ($_FILES[$key] as $k => $v)
        {
            foreach (is_array($v) ? $v : array($v) as $sk => $sv)
            {
                if (!key_exists($sk, $files))
                {
                    $files[$sk] = array();
                }

                $files[$sk][$k] = $sv;
            }
        }

        return $files;
    }

    /**
     * Extract file extension from file name
     *
     * @param   string  file name
     *
     * @return  string
     */
    public function get_file_extension($file_name)
    {
        $file_ext = '';
        $feindex = mb_strrpos($file_name, '.');

        // check file has name and extension
        if ($feindex)
        {
            // get file extension
            $file_ext = mb_substr($file_name, $feindex + 1);
        }

        return mb_strtolower($file_ext);
    }

    /**
     * Get non existing file name
     *
     * @param   string  file name to get extension
     * @param   string  path to check
     *
     * @return  string
     */
    public function get_new_filename($file_name, $path)
    {
        $file_ext = $this->get_file_extension($file_name);

        if ($file_ext !== '')
        {
            $file_ext = '.' . $file_ext;
        }

        $file_name = md5(uniqid(mt_rand(), TRUE)) . $file_ext;

        if (!preg_match('#/$#', $path))
        {
            $path .= '/';
        }

        while (file_exists($path . $file_name))
        {
            $file_name = md5(uniqid(mt_rand(), TRUE)) . $file_ext;
        }

        return $file_name;
    }

    /**
     * Create thumbnail
     *
     * @param   string  file name with path
     * @param   string  output path
     * @param   array   user config
     *
     * @return  bool
     */
    public function create_thumb($file_name, $path, $uconfig = array())
    {
        $config                     = array();
        $config['image_library']    = 'gd2';
        $config['create_thumb']     = TRUE;
        $config['maintain_ratio']   = TRUE;
        $config['thumb_marker']     = '';
        $config['width']            = 256;
        $config['height']           = 256;

        foreach ($uconfig as $k => $v)
        {
            $config[$k] = $v;
        }

        if (!preg_match('#/$#', $path))
        {
            $path .= '/';
        }

        $config['source_image']     = $file_name;
        $config['new_image']        = $path . mb_substr($file_name, mb_strrpos($file_name, '/') + 1);

        $ci = &get_instance();
        $ci->image_lib->initialize($config);

        $status = $ci->image_lib->resize();

        $ci->image_lib->clear();

        return $status;
    }

    /**
     * Upload attachments
     *
     * @param   string   attachment field
     * @param   array    params
     *
     * @return  array
     */
    public function upload($afield, $params)
    {
        /*
            $params fields

            attach_count    - int       number of files
            attach_size     - int       size in KB
            attach_types    - array     allowed file types
            attach_path     - string    destination path
            preserve_keys   - bool      preserve file keys
        */

        $attach_info = array();
        $attachments = array();
        $this->upload_error = NULL;

        foreach (array_slice($this->files($afield), 0, $params['attach_count'], $params['preserve_keys']) as $k => $_attach)
        {
            if (is_uploaded_file($_attach['tmp_name'])
                && $_attach['error'] === UPLOAD_ERR_OK
                && $_attach['size'])
            {
                if ($params['preserve_keys'])
                {
                    $attachments[$k] = $_attach;
                }
                else
                {
                    $attachments[] = $_attach;
                }
            }
        }

        if (!$attachments)
        {
            return $attach_info;
        }

        $max_filesize = $params['attach_size'] * 1024;
        $allowed_filetypes = array();
        $mimes = get_mimes();

        foreach ($params['attach_types'] as $k)
        {
            $allowed_filetypes[$k] = array();

            if (isset($mimes[$k]))
            {
                $allowed_filetypes[$k] = is_array($mimes[$k]) ? $mimes[$k] : array($mimes[$k]);
            }
        }

        unset($mimes);

        // validate cached attachments
        foreach ($attachments as $_attach)
        {
            // check file size
            if ($_attach['size'] > $max_filesize)
            {
                $this->upload_error = 'Unable to upload the file ' . $_attach['name'] . '. The uploaded file exceeds the maximum upload file size limit.';
                break;
            }

            // validate file extension
            $file_ext = $this->get_file_extension($_attach['name']);

            if (!$file_ext || !isset($allowed_filetypes[$file_ext]))
            {
                $this->upload_error = 'Unable to upload the file ' . $_attach['name'] . '. The file type you are attempting to upload is not allowed.';
                break;
            }

            // validate mime type
            $mime_type = mime_content_type($_attach['tmp_name']);
            $mime_ext = '';

            foreach ($allowed_filetypes as $k => $v)
            {
                if (in_array($mime_type, $v))
                {
                    $mime_ext = $k;
                    break;
                }
            }

            // validate file extension
            if (!$mime_ext)
            {
                $this->upload_error = 'Unable to upload the file ' . $_attach['name'] . '. The file type you are attempting to upload is not allowed.';
                break;
            }
        }

        if ($this->upload_error)
        {
            return $attach_info;
        }

        foreach ($attachments as $k => $_attach)
        {
            $file_name = $this->get_new_filename($_attach['name'], $params['attach_path']);

            // move validated files
            if (!@copy($_attach['tmp_name'], $params['attach_path'] . $file_name))
            {
                if (!@move_uploaded_file($_attach['tmp_name'], $params['attach_path'] . $file_name))
                {
                    $this->upload_error = 'Unable to upload the file ' . $_attach['name'] . '. Please contact the system administrator of this site.';
                    break;
                }
            }

            if ($params['preserve_keys'])
            {
                $attach_info[$k] = $file_name;
            }
            else
            {
                $attach_info[] = $file_name;
            }
        }

        return $attach_info;
    }

    /**
     * Get upload error
     *
     * @return  string
     */
    public function get_upload_error()
    {
        return $this->upload_error;
    }
}