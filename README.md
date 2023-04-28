# ethereum-wallet
[![PHP](https://github.com/web3p/ethereum-wallet/actions/workflows/php.yml/badge.svg)](https://github.com/web3p/ethereum-wallet/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/web3p/ethereum-wallet/branch/master/graph/badge.svg)](https://codecov.io/gh/web3p/ethereum-wallet)
[![Licensed under the MIT License](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/web3p/ethereum-wallet/blob/master/LICENSE)

Ethereum wallet.

# Install

```
composer require web3p/ethereum-wallet
```

# Usage

Generate a new wallet:

```php
use Web3p\EthereumWallet\Wallet;

$wallet = new Wallet();
$mnemonicLength = 15;
$wallet->generate($mnemonicLength);

// $wallet->address;
// danger zone, if the data was leaked, money would be stolen
// $wallet->privateKey;
// $wallet->mnemonic;
```

Recover wallet from mnemonic:

```php
use Web3p\EthereumWallet\Wallet;

$wallet = new Wallet();
$mnemonic = '..........';
$wallet->fromMnemonic($mnemonic);

// $wallet->address;
// danger zone, if the data was leaked, money would be stolen
// $wallet->privateKey;
// $wallet->mnemonic;
```

# API

### Web3p\EthereumWallet\Wallet

#### setWordlist

Set different mnemonic wordlist, the default is english.

`setWordlist(WordList $wordlist)`

wordList - \BitWasp\Bitcoin\Mnemonic\WordList

###### Example

```php
use Web3p\EthereumWallet\Wallet;
use Web3p\EthereumWallet\Wordlist\BIP39ChineseTraditionalWordList;

$wallet = new Wallet();
$zh_TW_wordlist = new BIP39ChineseTraditionalWordList;
$wallet->wordlist = $zh_TW_wordlist;
```

#### generate

Returns a new wallet for the given mnemonic length.

`generate(int $mnemonicLength)`

mnemonicLength - integer.

###### Example

* Generate a new wallet with 12 mnemonic.

```php
use Web3p\EthereumWallet\Wallet;

$wallet = new Wallet();
$wallet->generate(12);
```

#### fromMnemonic

Returns a wallet recover from the mnemonic.

`fromMnemonic(string $mnemonic)`

mnemonic - string.

###### Example

* Recover from a wallet.

```php
use Web3p\EthereumWallet\Wallet;

$wallet = new Wallet();
$mnemonic = '..........';
$wallet->fromMnemonic($mnemonic);
```

# License
MIT
