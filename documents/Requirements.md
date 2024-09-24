# Requirements

This document contains the requirements for the Runtime Records website. The document first describes the user requirements.  Next the non-functional requirements are specified.

---

## User requirement descriptions
The user requirements are described as a unique id for the requirement. Then the different actors and their action they would like to perform on the website. For this website there are three different actors; A visitor, user and admin. A visitor is somebody who visits the website, without being registered. A user is somebody who is registered. An admin is someone is responsible for maintaining the website.
|Id|Actor|Action description|
---|---|:---:
|UR-1|Visitor, user, Admin| As an Actor I want to be able to see an overview of all the items  |
|UR-2|Visitor, User| As an Actor I want to be able to filter the items by title, artist, genre, description and year|
|UR-3|Visitor, User, Admin| As an Actor I want to be able to see the details of an item|
|UR-4|Visitor, User| As an Actor I want to be able to add an item to my shopping cart|
|UR-5|Visitor, User| As an Actor I want to be able to view my shopping cart|
|UR-6|Visitor, User| As an Actor I want to be able to remove an item from my shopping cart|
|UR-7|Visitor, User| As an Actor I want to be able to purchase all items from my shopping cart|
|UR-8|Visitor | As an Actor I want to be able to register myself for the website|
|UR-9|User| As an Actor I want to be able to login to the website|
|UR-10|User| As an Actor I want to be able to set my preference in items|
|UR-11|User| As an Actor I want to be able to see an overview of my purchase history|
|UR-12|Admin| As an Actor I want to be able to add items to the catalog|
|UR-13|Admin| As an Actor I want to be able to remove items from the catalog|
|UR-14|Admin| As an Actor I want to be able to edit items from the catalog|
|UR-15|Admin| As an Actor I want to be able to see an overview of all orders with their status|

---

## Non functional requirements
|Id|Requirement|
---|:---|
|NFR-1|When an item is put in the cart it is marked as "RESERVED"|
|NFR-2|When an item isn't available anymore it is marked as "SOLD OUT"|
|NFR-3|When an order or item is cancelled, the stock will be updated again|
|NFR-4|When an item is sent, the status will be set to "SHIPPED"|
NEw in collection

## Educational requirement
The application must be object orientated