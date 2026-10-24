#include <iostream> 
#include <fstream> 
#include <vector>   
#include <string>  
using namespace std;
class Account {
protected:
    string name;           
    int accountNumber;    
    double balance;       
    string password;     
public:
    Account(string n = "غير معروف", int acc = 0, double bal = 0.0, string pass = "0000")
        : name(n), accountNumber(acc), balance(bal), password(pass) {}


    virtual void showInfo() const {
        cout << "------------------------------\n";
        cout << "الاسم: " << name << endl;
        cout << "رقم الحساب: " << accountNumber << endl;
        cout << "الرصيد: " << balance << " $" << endl;
        cout << "------------------------------\n";
    }
    int getAccountNumber() const { return accountNumber; }
    string getPassword() const { return password; }
    virtual void deposit(double amount) {
        if (amount > 0) {
            balance += amount;   
            cout << "تم الإيداع بنجاح.\n";
        } else {
            cout << "مبلغ غير صالح.\n";
        }
    }
    virtual void withdraw(double amount) {
        if (amount > 0 && amount <= balance) {
            balance -= amount;        
            cout << "تم السحب بنجاح.\n";
        } else {
            cout << "رصيد غير كافٍ أو مبلغ غير صالح.\n";
        }
    }

    double getBalance() const { return balance; }
    //دالة تجريدية لتعريف نوع الحساب
    virtual string getType() const = 0;

    virtual void save(ofstream &out) const {
        out << getType() << " " << name << " " << accountNumber << " "
            << balance << " " << password << endl;
    }
};

// 🔹 كلاس الحساب التوفيري مشتق
class SavingAccount : public Account {
public:
    SavingAccount(string n = "غير معروف", int acc = 0, double bal = 0.0, string pass = "0000")
        : Account(n, acc, bal, pass) {}

    string getType() const override { return "Saving"; }
};

// 🔹 كلاس الحساب الجاري مشتق
class CurrentAccount : public Account {
public:
    CurrentAccount(string n = "غير معروف", int acc = 0, double bal = 0.0, string pass = "0000")
        : Account(n, acc, bal, pass) {}

    string getType() const override { return "Current"; }
};

// 🔹 كلاس نظام البنك 
class BankSystem {
private:
    vector<Account*> accounts;  // تخزين جميع الحسابات في مصفوفة 

public:
    
    void createAccount() {
        string name, pass;
        int accNum, type;
        double balance;

        cout << "أدخل اسم العميل: ";
        cin >> name;
        cout << "أدخل رقم الحساب: ";
        cin >> accNum;
        cout << "أدخل الرصيد المبدئي: ";
        cin >> balance;
        cout << "أدخل كلمة المرور: ";
        cin >> pass;
        cout << "اختر نوع الحساب (1: توفير - 2: جاري): ";
        cin >> type;

        // 🔹 إنشاء الحساب بناءً على نوعه
        if (type == 1)
            accounts.push_back(new SavingAccount(name, accNum, balance, pass));
        else
            accounts.push_back(new CurrentAccount(name, accNum, balance, pass));

        cout << "تم إنشاء الحساب بنجاح!\n";
    }

    // 🔹 عرض كل الحسابات
    void showAllAccounts() {
        if (accounts.empty()) {
            cout << "لا توجد حسابات بعد.\n";
            return;
        }
        for (auto acc : accounts)
            acc->showInfo();   
    }

    // 🔹 البحث عن حساب باستخدام رقم الحساب وكلمة المرور
    Account* findAccount(int accNum, string pass) {
        for (auto acc : accounts) {
            if (acc->getAccountNumber() == accNum && acc->getPassword() == pass)
                return acc;
        }
        return nullptr;  // إذا لم يتم العثور على الحساب
    }

    // 🔹 إيداع الأموال في حساب معين
    void depositMoney() {
        int accNum;
        string pass;
        double amount;

        cout << "رقم الحساب: ";
        cin >> accNum;
        cout << "كلمة المرور: ";
        cin >> pass;

        Account* acc = findAccount(accNum, pass);
        if (acc) {
            cout << "أدخل مبلغ الإيداع: ";
            cin >> amount;
            acc->deposit(amount);  // استدعاء دالة الإيداع
        } else {
            cout << "الحساب غير موجود أو كلمة مرور خاطئة.\n";
        }
    }

    // 🔹 سحب الأموال من حساب معين
    void withdrawMoney() {
        int accNum;
        string pass;
        double amount;

        cout << "رقم الحساب: ";
        cin >> accNum;
        cout << "كلمة المرور: ";
        cin >> pass;

        Account* acc = findAccount(accNum, pass);
        if (acc) {
            cout << "أدخل مبلغ السحب: ";
            cin >> amount;
            acc->withdraw(amount);  // استدعاء دالة السحب
        } else {
            cout << "الحساب غير موجود أو كلمة مرور خاطئة.\n";
        }
    }

    // 🔹 حفظ جميع الحسابات في ملف نصي
    void saveToFile() {
        ofstream file("bank_data.txt");
        if (!file) {
            cout << "خطأ أثناء فتح الملف للحفظ.\n";
            return;
        }

        for (auto acc : accounts)
            acc->save(file);  // استدعاء دالة save لكل حساب

        file.close();
        cout << "تم حفظ البيانات في الملف بنجاح.\n";
    }

    // 🔹 دالة تدمير (Destructor) لتحرير الذاكرة المخصصة للحسابات
    ~BankSystem() {
        for (auto acc : accounts)
            delete acc;
    }
};
int main() {
    BankSystem bank;  
    int choice;       
    while (true) {
        cout << "\n========== نظام البنك المتكامل ==========\n";
        cout << "1. إنشاء حساب جديد\n";
        cout << "2. عرض كل الحسابات\n";
        cout << "3. إيداع أموال\n";
        cout << "4. سحب أموال\n";
        cout << "5. حفظ البيانات والخروج\n";
        cout << "اختيارك: ";
        cin >> choice;
        switch (choice) {
            case 1: 
                bank.createAccount();   // استدعاء دالة إنشاء حساب جديد
                break;
            case 2: 
                bank.showAllAccounts(); // عرض جميع الحسابات المخزنة
                break;
            case 3: 
                bank.depositMoney();    // إيداع الأموال في حساب محدد
                break;
            case 4: 
                bank.withdrawMoney();   // سحب الأموال من حساب محدد
                break;
            case 5: 
                bank.saveToFile();      // حفظ جميع الحسابات في ملف نصي
                cout << "تم الحفظ والخروج.\n";
                return 0;               // إنهاء البرنامج
            default: 
                cout << "خيار غير صالح.\n"; // إذا أدخل المستخدم خيار غير موجود
                break;
        }
    }
}