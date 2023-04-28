<?php

/**
 * This file is part of ethereum-wallet package.
 * 
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 * 
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3p\EthereumWallet;

use BitWasp\Bitcoin\Mnemonic\MnemonicFactory;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39SeedGenerator;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39Mnemonic;
use BitWasp\Bitcoin\Mnemonic\Bip39\Wordlist\EnglishWordList;
use BitWasp\Bitcoin\Mnemonic\WordList;
use BitWasp\Bitcoin\Key\Factory\HierarchicalKeyFactory;
use Web3p\EthereumUtil\Util;
use InvalidArgumentException;

/**
 * TODO: export/import version 1, 2, 3 of wallet
 */
class Wallet
{
    /**
     * wordlist
     * 
     * @var \BitWasp\Bitcoin\Mnemonic\WordList
     */
    protected $wordlist;

    /**
     * util
     * 
     * @var \Web3p\EthereumUtil\Util
     */
    protected $util;

    /**
     * privateKey
     * 
     * @var string
     */
    protected $privateKey;

    /**
     * mnemonic
     * 
     * @var string
     */
    protected $mnemonic;

    /**
     * publicKey
     * 
     * @var string
     */
    protected $publicKey;

    /**
     * address
     * 
     * @var string
     */
    protected $address;

    /**
     * defaultPath
     * 
     * @var string
     */
    protected $defaultPath = '44\'/60\'/0\'/0/0';

    /**
     * construct
     * 
     * @param \BitWasp\Bitcoin\Mnemonic\WordList $wordlist
     * @return void
     */
    public function __construct(WordList $wordlist = null)
    {
        if (!$wordlist) {
            $wordlist = new EnglishWordList;
        }
        $this->wordlist = $wordlist;
        $this->util = new Util;
    }

    /**
     * get
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], []);
        }
        return false;
    }

    /**
     * set
     * 
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], [$value]);
        }
        return false;
    }

    /**
     * getMnemonic
     * 
     * @return string
     */
    public function getMnemonic()
    {
        return $this->mnemonic;
    }

    /**
     * getPrivateKey
     * 
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * getPublicKey
     * 
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * getAddress
     * 
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * setWordlist
     * 
     * @param \BitWasp\Bitcoin\Mnemonic\WordList $wordlist
     * @return void
     */
    public function setWordlist(WordList $wordlist)
    {
        $this->wordlist = $wordlist;
    }

    /**
     * fromMnemonic
     * 
     * @param string $mnemonic mnemonic string
     * @param string $path relative hd path (remove m prefix)
     * @return $this
     */
    public function fromMnemonic(string $mnemonic, string $path = '')
    {
        $factory = MnemonicFactory::bip39($this->wordlist);
        $entropy = $factory->mnemonicToEntropy($mnemonic);
        $mnemonic = $factory->entropyToMnemonic($entropy);
        $generator = new Bip39SeedGenerator();
        $seed = $generator->getSeed($mnemonic);
        $hdFactory = new HierarchicalKeyFactory();
        $key = $hdFactory->fromEntropy($seed);
        if (strlen($path) == 0) {
            $path = $this->defaultPath;
        }
        $key = $key->derivePath($path);
        $privateKey = $key->getPrivateKey()->getBuffer()->getHex();
        $publicKey = $this->util->privateKeyToPublicKey($privateKey);
        $address = $this->util->publicKeyToAddress($publicKey);

        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->mnemonic = $mnemonic;
        $this->address = $address;
        return $this;
    }

    /**
     * generate
     * 
     * @param int $mnemonicLength mnemonic length
     * @param string $path relative hd path (remove m prefix)
     * @return $this
     */
    public function generate(int $mnemonicLength, string $path = '')
    {
        if (($mnemonicLength % 3) !== 0 || $mnemonicLength < 12 || $mnemonicLength > 24) {
            throw new InvalidArgumentException("The mnemonic length wasn't allowed");
        }
        $entropyBitsLength = ($mnemonicLength * 11 * 32) / 33;
        $factory = MnemonicFactory::bip39($this->wordlist);
        $mnemonic = $factory->create($entropyBitsLength);
        $generator = new Bip39SeedGenerator();
        $seed = $generator->getSeed($mnemonic);
        $hdFactory = new HierarchicalKeyFactory();
        $key = $hdFactory->fromEntropy($seed);
        if (strlen($path) == 0) {
            $path = $this->defaultPath;
        }
        $key = $key->derivePath($path);
        $privateKey = $key->getPrivateKey()->getBuffer()->getHex();
        $publicKey = $this->util->privateKeyToPublicKey($privateKey);
        $address = $this->util->publicKeyToAddress($publicKey);

        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->mnemonic = $mnemonic;
        $this->address = $address;
        return $this;
    }
}