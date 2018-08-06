# Greetings!

## Description:
This is one of the way how to realize multiple discount system.
Everything is based on DiscountRuleInterface. This is something very
close with Rule Engine, but more simple. 

In DiscountManager\Rule you can find available rules. Every rule
are stored in db:table rules. You can use any rule and
create own Discount (db:table discounts) with different rule value,
discount amount and product category target. 

When system will receive a request with Order, DiscountManager
will get all active Discounts (db:table discounts) and it will run
every rule of every discount, which will calculate and return a discount.
Then DiscountManager will save a total discount for order in db:table discount_history
and every applied in discount db:table applied_discounts. This data will be
serialized and returned as clear and informative response.

## Installation:
1. Make git clone *this repository*
2. Add in your hosts 127.0.0.1      discounts.local
3. CD to cloned folder and run sudo ./build.sh (rerun if failed on refuse)
4. Open http://discounts.local/api/doc/
5. Enjoy!

## How to enjoy?
1. Press on green button POST
2. Press Try it out
3. Press Execute
4. Receive your response

P.S You can get else json examples from request-examples folder
P.S. Try to run multiple multiple-discounts.json to see how multiple discount will be applied

## Warning - Not for production!
Unit tests missed

Real API communication channel missed

Order deserialization missed

Assertions missed

Order product category logic missed
