<div id="help-content">
    <h2 id="concept-purpose-goal">Concept/Purpose/Goal</h2>
    <p>My brother wasn't aware of any budgeting apps for users who do not have a fixed income. In other words, whose income is unknown. It can vary from week to week. Apparently, other budgeting apps assume the user knows what their income will be.</p>
    <p>He had predetermined expenses, so he didn't want to overspend on the less important expenses and then not have enough to spend on the predetermined expenses.</p>
    <p>So he wanted to be able to specify in advance his predetermined expenses, and then for each of his remaining expense categories, to be able to specify a percentage of what he had remaining to spend on those.</p>
    <p>We call the 'predetermined' expenses 'fixed', and the others 'flex', i.e., flexible, because they are a percentage of what is remaining.</p>

    <h2 id="tags-link">Tags/Budgets</h2>
    <p>To create budgets, delete them, or edit them, go to one of the pages from the budgets dropdown menu.</p>
    <p>Please note that I have recently changed the way the tags and budgets work and haven't really decided yet on the appropriate terminology. Currently, the tags referred to below are now known as budgets, and 'tags without budgets' are known as 'unassigned budgets.'</p>

    <p>A tag is a category of something you spend or earn money on. For example, food or clothes. It is these tags that you specify a budget for. You choose the budget type (either fixed or flex).</p>
    <p>For a fixed budget, you specify the amount per month you need to spend on that tag.</p>
    <p>For a flex budget, you specify the percentage of what is remaining that you want to spend on that tag.</p>
    <p>You can specify a starting date for a tag.</p>
    <p>For the tags with fixed budgets, the starting date is taken into account. The amount calculated (the 'cumulative' column) is the amount column multiplied by the month number.</p>
    <p>We're not sure if the starting date for tags with flex budgets has a point yet. :)</p>
    <p>The tables on this budgets page show you totals for each tag you have given a budget for.</p>
    <p>The fixed budgets table shows you, for each of those tags:</p>
    <ul>
        <li>the amount you have budgeted per month</li>
        <li>the starting date</li>
        <li>the month number</li>
        <li>the amount per month multiplied by the month number</li>
        <li>how much you have spent on the tag after it's starting date</li>
        <li>how much you have spent on the tag before its starting date</li>
        <li>how much you have received on the tag (for a tag like 'business' you might have both expenses as well as income)</li>
        <li>how much you have remaining</li>
    </ul>
    <p>The flex budgets table shows you</p>
    <ul>
        <li>the percentage figure you have budgeted for the tag</li>
        <li>the calculated amount you have budgeted per month (the percentage figure percent of your remaining balance)</li>
        <li>the starting date</li>
        <li>the month number</li>
        <li>how much you have spent on the tag after it's starting date</li>
        <li>how much you have received on the tag</li>
        <li>how much you have remaining</li>
    </ul>
    <p>Each table has a totals row, showing you the totals for each column, so you can see things such as the total amount you have budgeted, spent, or remaining on either the fixed or flex budgets.</p>
    <p>Tags you might give a fixed budget to are:</p>
    <ul>
        <li>groceries</li>
        <li>rent</li>
        <li>licenses</li>
        <li>insurance</li>
        <li>conferences</li>
    </ul>
    <p>Tags you might give a flex budget to are:</p>
    <ul>
        <li>eating out</li>
        <li>entertainment</li>
        <li>recreation</li>
        <li>holidays</li>
        <li>gifts</li>
        <li>books</li>
        <li>clothes</li>
    </ul>
    <h2 id="accounts-link">Accounts</h2>
    <p>To get started using the application, you'll need to have at least one account created. To do so, go to the accounts page from the <i class="fa fa-bars"></i> menu.</p>
    <p>Accounts are for keeping track of where your money is, so you might have a few different bank accounts as well as a cash account.</p>

    <h2 id="transactions-link">Transactions</h2>
    <p>You can enter transactions from the home page. (Click on the <i class="fa fa-home"></i> button in the menu to go there.)</p>
    <h4 id="allocating">Allocating the totals</h4>
    <p>A transaction can be given multiple tags. However, if more than one of those tags has been given a budget, you'll need to specify the amounts (either a fixed amount or a percentage of the total of the transaction) that you want to be considered spent off your budgeted tags.</p>
    <p>It wouldn't make sense, and it would wreck up the totals, if you were to spend $10 and have $20 considered as spent because the transaction had two budgets associated with it.</p>
    <h4 id="reconciling">Reconciling</h4>
    <p>You can mark transactions as reconciled (by checking the transaction's checkbox). This is so that when you receive your bank statement:</p>
    <ul>
        <li>You can check the transactions you have entered into the app match those of your bank statement. (You might have made a mistake entering your transaction, or forgotten to enter it.)</li>
        <li>Vice versa. (There might be a transaction on your bank statement that was made by someone else, and you can see it wasn't made by you by checking you didn't enter it into the app.)</li>
    </ul>
    <h2 id="savings">Savings</h2>
    <p>This feature is relatively new and still has plenty of work to be done on. When income is earned, a percentage of what is earned is taken from the balance and put into savings.</p>
    <p>Currently it is 10%, but it is planned to make that customisable.</p>
    <p>The action is reversed when an income transaction is deleted (and on other similar occasions).</p>

    <h2 id="RB">Remaining Balance</h2>
    <p>The remaining balance is the balance before the flex budgets are calculated. It is the figure that the flex budgets are a percentage of.</p>
    <p>It has been perhaps the trickiest and most important figure to figure out, since the flexible budgets depend on it, and the flexible budgets are the point of the app!</p>
    <p>We're still not sure if we have it right, but currently the formula is:</p>
    <ul>
        <li>+ total credit ever earned</li>
        <li>- remaining fixed budget</li>
        <li>- total expenses with no associated budget</li>
        <li>- total spent on flex budgets before their starting dates</li>
        <li>- total spent on fixed budgets before their starting dates</li>
        <li>- total spent on fixed budgets after their starting dates</li>
        <li>- savings</li>
    </ul>

    <h2 id="totals">Totals</h2>
    <p>A lot of the figures in the totals sidebar are probably unnecessary for users, but they have been helpful in getting the totals working and understanding what is going on. A lot of them were for figuring out the remaining balance. Some are just the same figures as in the budget table totals columns.</p>
    <p>Probably the most relevant figures for the user are: credit, debit, remaining balance, savings, and the remaining columns in the budget tables, so the user can see how much they have left to spend on each tag.</p>
    <p>When actions are made, the changes made to the totals are displayed in the 'changes' column.</p>
    <h2>This page</h2>
    <p>You can find this page again in the help menu.</p>
</div>