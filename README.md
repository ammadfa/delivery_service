# Delivery Service
Delivery service API for processing orders for Personal or Enterprise deliveries

1. Personal Vs Enterprise Deliveries

Looking at all the delivery_types provided in the JSON payload two main types were identified which was either the
delivery is going to be personal or an enterprise.

Since enterprise delivery order is going to be using the same fields as the personal delivery order it made sense to
use Inheritance to create a base class "DeliveryOrder" for doing personal delivery orders (doesn't matter if the order
is express delivery or not). Another class was created "EnterpriseDeliveryOrder" which extends from "DeliveryOrder".
Having this setup made sharing of code quite simple and I could further extend the structure for new delivery types
(if required).

2. Email Campaign inspired deliveries

Some delivery orders could have originated from an email campaign so the requirement was to notify the marketing
department after processing the order. Having a link for ANY delivery order to be linked to a campaign (even enterprise
deliveries) was something that was assumed.

3. Delivery Types & Workflow

Once delivery orders are recorded within the system we then go about processing them and we tackled this problem by
introducing a Provider concept located in "src/Provider" dir.

"DeliveryBase" is the base abstract class it has some shared logic/functionality that is common across a Personal or
Enterprise delivery processing. For instance since I have assumed that Email Campaign can be linked to any delivery
type we can process the marketing notification workflow as a postProcess task.

"PersonalDelivery" or "EnterpriseDelivery" both extends from abstract DeliveryBase but they both can have their own
workflow. For instance EnterpriseDelivery had a preProcess task of validating the enterprise info from the payload.

4. Automated Testing

Automation testing was implemented using Codeception unit tests (located in tests/unit dir).
A few unit tests were created to demonstrate the possible scenarios that could break the processing workflow.

Test #1: Process dummy orders payload with no customer info
- ensures we do not process any orders that have no customer info

Test #2: Process dummy orders payload with no enterprise info
- ensures we do not process any orders that we have no enterprise info (as we require this info for validation)

Test #3: Process dummy orders payload
- test designed to make sure we can process orders that actually have all the required info