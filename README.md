GeSFi
=====

http://stevefuchs.fr/wordpress/gesfi/

Development notes
=================

record_type:

- money transfer from an account to another account :

1x = income

2x = outcome

0 = between account (money transfer)

    20 (-, debit) linked to 10 (+, credit)

1 = from account to virtual duo account

    21 = - or debit from private account (expected to be linked to 22 or 12 in virtual duo account)
        These records are not considered as an expense but as a debit
    11 = + or credit from duo account (partner)
        (expected to be linked to 12 in virtual duo account)

2 = going or coming from outside (income, expense)

    12 = wage, gift, ...
    22 = expense

Duo virtual account allowed operations:

    12 (credit from partners) -> Account -> 22 (expense)

Duo real account allowed operations:

    10 (credit between account) -> Account -> 20 (debit between account)
                                       -> 22 (expense)

Private real account allowed operations:

    10 (credit between account)     -> Account -> 20 (debit between account)
    11 (credit coming from partner)            -> 22 (expense)
    12 (money coming from outside)

== Glossaire de l'application ==


Balance
Transfer
Credit
Debit
Payment
Income



Record

Account : compte bancaire (comptes d'utilisation courante)

* Payment
* Transfer

Investment Account : placement bancaire

* Deposit
* Withdrawal

== Coding standards ==

Classes naming:
- standard: AaaaAaaaAaaa
- inherited: AaaaAaaaAaaa_BbbbbBbbb
Use of singular in names

Quotes:
- HTML tags "
- Javascript within HTML tags '
- PHP strings '
but Strings to translate: "
- SQL queries delimiters within PHP "
- SQL queries in-string delimiters '

Examples:
 $row['record_group_id']
 $sql = sprintf("update {TABLEPREFIX}record set designation='%s' where record_group_id = '%s'", ...
