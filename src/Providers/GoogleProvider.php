<?php

namespace duncan3dc\Speaker\Providers;

/**
 * Convert a string of a text to spoken word audio.
 */
class GoogleProvider extends AbstractProvider
{
    /**
     * @var string $language The language to use.
     */
    protected $language = "en";
    
    /**
     * @var string $availableLanguages The available languages.
     *
     * Visit https://cloud.google.com/translate/docs/languages for available language code
     */
    protected $availableLanguages = [ "af", "sq", "am", "ar", "hy", "az", "eu", "be", "bn", "bs", "bg", "ca", "ceb", "zh-CN", "zh-TW", "co", "hr", "cs", "da", "nl", "en", "eo", "et", "fi", "fr", "fy", "gl", "ka", "de", "el", "gu", "ht", "ha", "haw", "iw", "hi", "hmn", "hu", "is", "ig", "id", "ga", "it", "ja", "jw", "kn", "kk", "km", "ko", "ku", "ky", "lo", "la", "lv", "lt", "lb", "mk", "mg", "ms", "ml", "mi", "mr", "mn", "my", "ne", "no", "ny", "ps", "fa", "pl", "pt", "pa", "ro", "ru", "sm", "gd", "sr", "st", "sn", "sd", "si", "sk", "sl", "so", "es", "su", "sw", "sv", "tl", "tg", "ta", "te", "th", "tr", "uk", "ur", "uz", "vi", "cy", "xh", "yi", "yo", "zu" ];
    
    /**
     * Create a new instance.
     *
     * @param string $language The language to use.
     */
    public function __construct($language = null)
    {
        if ($language !== null) {
            $this->setLanguage($language);
        }
    }


    /**
     * Set the language to use.
     *
     * @param string $language The language to use (eg 'en')
     *
     * @return static
     */
    public function setLanguage($language)
    {
        $language = trim($language);
        if (!in_array($language, $this->availableLanguages, true)) {
            throw new \InvalidArgumentException("Unexpected language code ({$language}), all the available languages (" . implode(", ", $this->availableLanguages) . ")");
        }

        $this->language = $language;

        return $this;
    }


    /**
     * Get the current options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            "language"  =>  $this->language,
        ];
    }


    /**
     * Get the available languages.
     *
     * @return array
     */
    public function getAvailableLanguages()
    {
        return $this->availableLanguages;
    }


    /**
     * Convert the specified text to audio.
     *
     * @param string $text The text to convert
     *
     * @return string The audio data
     */
    public function textToSpeech($text)
    {
        if (strlen($text) > 100) {
            throw new \InvalidArgumentException("Only messages under 100 characters are supported");
        }

        return $this->sendRequest("http://translate.google.com/translate_tts", [
            "q"         =>  $text,
            "tl"        =>  $this->language,
            "client"    =>  "tw-ob",
            "ie"        =>  "UTF-8",
        ]);
    }
}
