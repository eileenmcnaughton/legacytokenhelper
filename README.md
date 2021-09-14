# legacytokenhelper

Helper extension for sites that relied on token anomalies pre 5.43

This extension may help sites if they relied on
oddball code in core - namely

1) passing 'case_id' to token hooks and
2) assigning the contact as a smarty variable to
the template, as returned by the rendered hooks.

## Known Issues

1) Whoa - when you DO pass case_id to token hooks it returns the wrong
contact.... this appears to be the behaviour we are 'saving'
2) Assigning the contact seems kinda pointless since only the
fields that there are tokens for are in the return values
3) oddly I couldn't make the define condition true. I just
hacked past it to test....


The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.2+
* CiviCRM (*FIXME: Version number*)

## Installation (Web UI)

Learn more about installing CiviCRM extensions in the [CiviCRM Sysadmin Guide](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/).

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl legacytokenhelper@https://github.com/FIXME/legacytokenhelper/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/FIXME/legacytokenhelper.git
cv en legacytokenhelper
```

## Getting Started

(* FIXME: Where would a new user navigate to get started? What changes would they see? *)
