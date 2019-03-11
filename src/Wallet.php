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

use BitWasp\BitcoinLib\BIP39\BIP39;
use BitWasp\BitcoinLib\BIP39\BIP39WordList;
use Web3p\EthereumUtil\Util;

/**
 * TODO: export/import version 1, 2, 3 of wallet
 */
class Wallet
{
    /**
     * wordlist
     * 
     * @var \BitWasp\BitcoinLib\BIP39\BIP39WordList
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
     * construct
     * 
     * @param \BitWasp\BitcoinLib\BIP39\BIP39WordList $wordlist
     * @return void
     */
    public function __construct(BIP39WordList $wordlist)
    {
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
     * @param BIP39WordList $wordlist
     * @return void
     */
    public function setWordlist(BIP39WordList $wordlist)
    {
        $this->wordlist = $wordlist;
    }

    /**
     * TODO: fromMnemonic
     * 
     * @return $this
     */
    // public function fromMnemonic() {}

    /**
     * generate
     * 
     * @return $this
     */
    public function generate()
    {
        $privateKey = BIP39::generateEntropy(256);
        $mnemonic = BIP39::entropyToMnemonic($privateKey, $this->wordlist);
        $publicKey = $this->util->privateKeyToPublicKey($privateKey);
        $address = $this->util->publicKeyToAddress($publicKey);

        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->mnemonic = $mnemonic;
        $this->address = $address;
        return $this;
    }
}