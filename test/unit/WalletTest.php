<?php

namespace Test\Unit;

use Test\TestCase;
use BitWasp\BitcoinLib\BIP39\BIP39EnglishWordList;
use Web3p\EthereumWallet\Wallet;
use Web3p\EthereumWallet\Wordlist\BIP39ChineseTraditionalWordList;

class WalletTest extends TestCase
{
    /**
     * testGenerate
     * 
     * @return void
     */
    public function testGenerate()
    {
        $wordlist = new BIP39EnglishWordList;
        $wallet = new Wallet($wordlist);
        $wallet = $wallet->generate(12);
        $this->assertEquals(64, strlen($wallet->privateKey));
        $this->assertEquals(42, strlen($wallet->address));
        $mnemonic = explode(" ", $wallet->mnemonic);
        $this->assertEquals(12, count($mnemonic));
    }

    /**
     * testSetWordlist
     * 
     * @return void
     */
    public function testSetWordlist()
    {
        $wordlist = new BIP39EnglishWordList;
        $wallet = new Wallet($wordlist);
        $zh_TW_wordlist = new BIP39ChineseTraditionalWordList;
        $wallet->wordlist = $zh_TW_wordlist;
        $wallet = $wallet->generate(15);
        $this->assertEquals(64, strlen($wallet->privateKey));
        $this->assertEquals(42, strlen($wallet->address));
        $mnemonic = preg_split("/\s/", $wallet->mnemonic);
        $this->assertEquals(15, count($mnemonic));
    }

    /**
     * testFromMnemonic
     * 
     * @return void
     */
    public function testFromMnemonic()
    {
        $wordlist = new BIP39EnglishWordList;
        $wallet = new Wallet($wordlist);
        $wallet = $wallet->generate(15);
        $wallet2 = new Wallet($wordlist);
        $wallet2->fromMnemonic($wallet->mnemonic);
        $this->assertEquals($wallet->privateKey, $wallet2->privateKey);
        $this->assertEquals($wallet->address, $wallet2->address);
        $this->assertEquals($wallet->mnemonic, $wallet2->mnemonic);
    }
}
