## Description:
This is one of the way how to realize multiple discount system.
Everything is based on DiscountRuleInterface. This is something very
close with Rule Engine, but more simple. 

In DiscountManager\Rule you can find available rules. Every rule
are stored in db:table rules. You can use any rule and
create own Discount (db:table discounts) with different rule value,
discount amount and product category target. That is providing scalability, in database
you can create, deactivate or update discounts based on available rules.

When system will receive a request with Order, DiscountManager
will get all active Discounts (db:table discounts) and it will run
every rule of every discount, this rules will calculate and return a discount.

Then DiscountManager will save a total discount for order in db:table discount_history
and every applied discount in discount db:table applied_discounts. This data will be
serialized and returned as clear and informative response.

## Installation:
1. linux terminal: git clone https://github.com/bog-h/discounts.git
2. Add in your hosts file a new host: 127.0.0.1      discounts.local
3. linux terminal: CD to your cloned folder and run command "sudo ./build.sh" (rerun if failed on refuse)

## How to run?
1. Open http://discounts.local/api/doc/ (this is a page of installed and configured NelmioApiDocBundle)
2. Press on row "POST /api/calculate-discount-for-order Calculate discount for customer order"
3. Press Try it out
4. Press Execute
5. Receive a response

You can execute different json examples like multiple-discounts.json from request-examples folder

## Warning - This is only abstraction
Use this code only for exploring, a lot of things are missed like
Real API communication channel,
Order deserialization,
Assertions, e.t.c.

