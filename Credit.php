<?php


class Credit
{
    public $sumCredit;
    public $receivingDate;
    public $interestRate;
    public $duration;
    public $payments;
    public $atDate;
    public $interestCredit;
    public $datePayment;

    public function calculationDebt($date): float {
        $dataArray = json_decode($date);
        if ($dataArray == null) {
            print_r("incoming data type error \n");
            return false;
        }
        $this->setVariables($dataArray);
        $result = $this->getIndebtedness();
        return $result;
    }

    public function setVariables($result) {
        $this->sumCredit = $result->loan->base;
        $this->receivingDate = $result->loan->date;
        $this->interestRate = $result->loan->percent;
        $this->duration = $result->loan->duration;
        $this->payments = $result->payments;
        $this->atDate = $result->atDate;
        $this->interestCredit = 0;
        $this->datePayment = $this->receivingDate;
    }

    public function getIndebtedness(): float {
        foreach ($this->payments as $paymant) {
            if ($this->atDate >= $paymant->date) {
                $days = $this->countDaysBetweenDates($this->datePayment, $paymant->date);

                $percentDays = $this->interestRate * $days;

                $this->interestCredit = round($this->sumCredit / 100 * $percentDays, 2);
                $this->interestCredit = $this->checkExcessPercentage($this->interestCredit);

                $this->creditRepayment($paymant->amount);

                $this->datePayment = $paymant->date;
            }
        }
        return $this->sumCredit + $this->interestCredit;
    }

    public function countDaysBetweenDates($date1, $date2): int {
        $seconds = intval(abs(strtotime($date1) - strtotime($date2)));
        return floor($seconds / (3600 * 24));
    }

    public function checkExcessPercentage($percent): float {
        if ($percent > $this->sumCredit * 3) {
            $percent = $this->sumCredit * 3;
        }
        return $percent;
    }

    public function creditRepayment($amount) {
        if ($amount > $this->interestCredit) {
            $amount = $amount - $this->interestCredit;
            $this->interestCredit = 0;
            $this->sumCredit = $this->sumCredit - $amount;
        }else {
            $this->interestCredit = $this->interestCredit - $amount;
        }
    }
}
